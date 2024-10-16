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
}