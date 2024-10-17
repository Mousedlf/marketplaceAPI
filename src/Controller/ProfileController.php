<?php

namespace App\Controller;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $apis = $this->getUser()->getBoughtAPIKeys();
        return $this->render('profile/index.html.twig',[
            "apis"=>$apis
        ]);
    }

    #[Route('/delete-account', name: 'delete_account')]
    public function deleteAccount(MailerService $mailerService): Response
    {

        // ajouter la suppression de compte avec modal ?

        $user = $this->getUser();
        $mailerService->sendAccountDeletionEmail($user->getEmail(), 'admin@example.com');

        return $this->redirectToRoute('app_home');
    }
}
