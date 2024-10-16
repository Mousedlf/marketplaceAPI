<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailerController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer, Request $request): Response
    {
        $email = (new Email())
            ->from($request->getPayload()->get('fromEmail') ?? throw new Exception('Email from not found'))
            ->to($request->getPayload()->get('toEmail') ?? throw new Exception('Email to send not found'))
            ->subject($request->getPayload()->get('subject') ?? throw new Exception('Subject not found'))
            ->text($request->getPayload()->get('message') ?? throw new Exception('Message not found'))
            // Ajouter template ici pour le mail
            ->html('');

        $mailer->send($email);
        return $this->redirectToRoute('app_login');
    }

    #[Route('/template/key')]
    public function displayTemplateOrder(): Response
    {
        return $this->render('mailer/layout/key.html.twig', [
            'mail' => "exemple@mail.com",
            'date_order' => "16/10/2024",
            'product_name' => "Clé API Fruits",
            'offre_name' => "10000 R.",
            'product_price' => 20,
            'activation_key' => "12345-6789-0435"
        ]);
    }

    #[Route('/template/user')]
    public function accountDeletion(): Response
    {
        return $this->render('mailer/layout/user.html.twig', [
            'mail' => "exemple@mail.com",
        ]);
    }
}