<?php

namespace App\Controller;

use App\Repository\APIRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(APIRepository $APIRepository): Response
    {
        $apis = $APIRepository->findAll();

        return $this->render('home/index.html.twig', [
            'apis' => $apis,
        ]);
    }
}
