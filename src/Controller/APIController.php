<?php

namespace App\Controller;

use App\Entity\API;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
