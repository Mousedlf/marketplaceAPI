<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MailerController extends AbstractController
{
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