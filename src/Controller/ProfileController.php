<?php

namespace App\Controller;

use App\Repository\PlatformAPIKeyRepository;
use App\Service\MailerService;
use App\Service\RequestToApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(RequestToApiService $service,PlatformAPIKeyRepository $platformAPIKeyRepository): Response
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

    #[Route('/api/key/{action}', name: 'api_key_action', requirements: ['action' => 'generate|remove'], methods: ['POST'])]
    public function handleApiKeyAction(Request $request, string $action,RequestToApiService $service): Response
    {

        $url = $request->request->get('revokeKeyRoute');
        $adminKey = $request->request->get('adminKey');
        $clientEmail = $request->request->get('email');

        if ($action === 'generate') {
            $service->generateNewKey($url, $adminKey, $clientEmail);
        } elseif ($action === 'remove') {
            $service->generateNewKey($url, $adminKey, $clientEmail, true);
        }

        return $this->redirectToRoute('app_profile');
    }
}
