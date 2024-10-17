<?php

namespace App\Controller;

use App\Entity\API;
use App\Entity\Offer;
use App\Service\CartService;
use Stripe\Checkout\Session;
use Stripe\Stripe;
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


    #[Route('/pay', name: 'app_pay')]
    public function pay(): Response
    {
        Stripe::setApiKey('sk_test_BQokikJOvBiI2HlWgH4olfQ2');

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:8000/cancel',
        ]);

        return $this->redirect($session->url, 303);
    }
}
