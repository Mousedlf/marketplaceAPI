<?php

namespace App\Controller;

use App\Entity\API;
use App\Entity\Offer;
use App\Service\CartService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function getCart(
        CartService $cartService,
    ): Response
    {
        return $this->render('cart/index.html.twig', [
            "cart" => $cartService->getCart(),
            "total" => $cartService->getTotal(),
        ]);
    }

    #[Route('/add/{api_id}/{offer_id}', name: 'app_cart_add_item')]
    public function addItem(
        CartService $cartService,
        #[MapEntity(id: 'api_id')] API $api,
        #[MapEntity(id: 'offer_id')] Offer $offer
    ): Response
    {
        $cartService->addItem($api, $offer);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove/{api_id}/{offer_id}', name: 'app_cart_remove_item')]
    public function removeItem(
        CartService $cartService,
        #[MapEntity(id: 'api_id')] API $api,
        #[MapEntity(id: 'offer_id')] Offer $offer
    ): Response
    {
        $cartService->removeItem($api);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/clear', name: 'app_cart_clear')]
    public function clearCart(CartService $cartService): Response
    {
        $cartService->emptyCart();
        return $this->json("empty cart");
    }
}
