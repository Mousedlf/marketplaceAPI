<?php

namespace App\Controller;

use App\Entity\API;
use App\Entity\Offer;
use App\Entity\PlatformAPIKey;
use App\Form\APIRegistrationType;
use App\Form\OfferType;
use App\Form\PlatformAPIKeyType;
use App\Entity\Order;
use App\Entity\UserAPIKey;
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

    #[Route('/api/register/new', name: 'register_new_api')]
    public function registerANewApi(
        Request $request,
        EntityManagerInterface $manager,
    ): Response
    {
       $api = new API();
        $form = $this->createForm(APIRegistrationType::class, $api);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $api->setCreatedBy($this->getUser());
            $manager->persist($api);
            $manager->flush();

            return $this->redirectToRoute('register_new_api_set_offers', [
                "id" => $api->getId(),
            ]);
        }

        return $this->render('api/new.html.twig', [
            "apiRegistrationForm" => $form->createView(),
        ]);
    }

    #[Route('/api/{id}/offers', name: 'register_new_api_set_offers')]
    public function addOffersToApi(
        Request $request,
        Api $api,
        EntityManagerInterface $manager,
    ): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setAPI($api);
            $manager->persist($offer);
            $manager->flush();

            return $this->redirectToRoute('register_new_api_set_offers', [
                "id" => $api->getId(),
                "api" => $api,
            ]);
        }

            return $this->render('api/newoffers.html.twig', [
                "offerForm"=> $form->createView(),
                "api" => $api,
        ]);
    }

    #[Route('/api/{id}/admin/key', name: 'register_new_api_admin_key')]
    public function inputAdminKey(
        Request $request,
        API $api,
        EntityManagerInterface $manager,
    ):Response
    {

        $platformAPIKey = new PlatformAPIKey();
        $form = $this->createForm(PlatformAPIKeyType::class, $platformAPIKey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $platformAPIKey->setAPI($api);

            // encode en base64 la clé admin avant de la mettre en DB
            $platformAPIKey->setValue(base64_encode($platformAPIKey->getValue()));

            $manager->persist($platformAPIKey);
            $manager->flush();

            return $this->redirectToRoute('app_home', []);
        }

        return $this->render('api/adminkey.html.twig', [
            "keyForm" => $form->createView(),
            "api" => $api,
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

        $newUserApiKey = new UserAPIKey();
        $newUserApiKey->setOfUser($user);
        $newUserApiKey->setActive(true);
        $newUserApiKey->setNbUsedRequests(0);
        $newUserApiKey->setNbPaidRequests($boughtRequests);

        $manager->persist($order);
        $manager->flush();

        $userApiKey = $returnContent["apiKey"];
        $mailerService->sendNewClientApiKeyMail($user->getEmail(),"Your brand new api key for".$api->getName(),$userApiKey,$order,$api,$offer);
        return $this->redirectToRoute('app_profile');
    }
}
