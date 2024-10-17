<?php

namespace App\Controller;

use App\Entity\API;
use App\Entity\Order;
use App\Repository\APIRepository;
use App\Repository\OfferRepository;
use App\Repository\PlatformAPIKeyRepository;
use App\Repository\UserRepository;
use App\Service\MailerService;
use App\Service\RequestToApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class APIController extends AbstractController
{
    #[Route('/api/{id}', name: 'show_api')]
    public function show(API $API): Response
    {
        return $this->render('api/show.html.twig', [
            'API' => $API,
        ]);
    }


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/api/createClient/{apiId}/{offerId}', name: 'create_new_user')]
    public function createUserAPIAndSendMailToUser(
        UserRepository $userRepository,
        int $apiId,
        APIRepository $APIRepository,
        PlatformAPIKeyRepository $platformAPIKeyRepository,
        Request $request,
        RequestToApiService $requestToApiService,
        MailerService $mailerService,
        OfferRepository $offerRepository,
        string $offerId,
        EntityManagerInterface $manager,
    ): Response
    {
        $json = $request->getContent();
        $body = json_decode($json, true);

        $api = $APIRepository->find($apiId);
        $user = $userRepository->find($this->getUser());
        $offer = $offerRepository->find($offerId);

        $baseUrl = $api->getBaseUrl();
        $requestUrl = $api->getClientCreationRoute();
        $fullUrl = $baseUrl . "/" . $requestUrl;

        $boughtRequests = $body["nbOfBoughtRequests"];
        $adminKey = $platformAPIKeyRepository->findOneBy(['api' => $api]);
        $clientEmail = $user->getEmail();

        $returnContent = $requestToApiService->createClient($fullUrl, $boughtRequests, $adminKey, $clientEmail);

        $order = new Order();
        $order->setByUser($user);
        $order->setTotal($offer->getPrice());
        $order->addAPI($api);

        $manager->persist($order);
        $manager->flush();

        $userApiKey = $returnContent["apiKey"];
        $mailerService->sendNewClientApiKeyMail($user->getEmail(),"Your brand new api key for".$api->getName(),$userApiKey,$order,$api,$offer);
        return $this->redirectToRoute('app_profile');
    }
}
