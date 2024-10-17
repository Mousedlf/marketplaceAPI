<?php

namespace App\Controller;

use App\Entity\API;
use App\Entity\Offer;
use App\Entity\Order;
use App\Entity\UserAPIKey;
use App\Service\CartService;
use App\Service\FetchApiData;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('', name: 'app_cart')]
    public function getCart(
        CartService $cartService,
    ): Response
    {
        //dd($cartService->getCart());
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

    #[Route('/remove/{api_id}', name: 'app_cart_remove_item')]
    public function removeItem(
        CartService $cartService,
        #[MapEntity(id: 'api_id')] API $api,
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

    #[Route('/stripe', name: 'app_stripe')]
    public function pay(
        CartService $cartService,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $cartItems = $cartService->getCart();
        $lineItems = [];

        foreach ($cartItems as $item) {
            $api = $item['api'];
            $offer = $item['offer'];

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $api->getName(),
                        'description' => $offer->getNbOfAvailableRequests() . ' requests available',
                    ],
                    'unit_amount' => $offer->getPrice() * 100,
                ],
                'quantity' => 1,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/cart/make/order',
            'cancel_url' => 'http://localhost:8000/cart',
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/make/order', name: 'app_order')]
    public function makeOrder(
        CartService $cartService,
        FetchApiData $fetchApiData,
        MailerService $mailerService,
        EntityManagerInterface $manager
    ): Response
    {
        $client = $this->getUser();

        foreach ($cartService->getCart() as $cartItem) {
            $apiKey = $fetchApiData->fetchGenerateKey($cartItem['api'], $cartItem['offer'], $client->getEmail());

            // create Order
            $order = new Order();
            $order->setByUser($client)
                ->addAPI($cartItem['api'])
                ->setTotal($cartItem['offer']->getPrice());

            $manager->persist($order);

            // create UserAPIKey
            $userApiKey = new UserApiKey();
            $userApiKey->setApi($cartItem['api'])
                ->setOfUser($client)
                ->setNbPaidRequests($cartItem['offer']->getNbOfAvailableRequests())
                ->setActive(true);

            // EM
            $manager->persist($userApiKey);
            $manager->flush();

            // send mail
            $mailerService->sendNewClientApiKeyMail($client->getEmail(), $apiKey, $order, $cartItem['api'], $cartItem['offer']);
        }

        $cartService->emptyCart();
        return $this->render('cart/validation.html.twig');
    }
}
