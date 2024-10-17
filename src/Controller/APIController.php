<?php

namespace App\Controller;

use App\Entity\API;
use App\Entity\Offer;
use App\Entity\PlatformAPIKey;
use App\Form\APIRegistrationType;
use App\Form\OfferType;
use App\Form\PlatformAPIKeyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

            $manager->persist($platformAPIKey);
            $manager->flush();

            return $this->redirectToRoute('app_home', []);
        }

        return $this->render('api/adminkey.html.twig', [
            "keyForm" => $form->createView(),
            "api" => $api,
        ]);
    }

}
