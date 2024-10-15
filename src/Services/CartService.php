<?php

namespace App\Services;

use App\Entity\API;
use App\Entity\Offer;
use App\Repository\APIRepository;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;
    private $apiRepo;
    private $offerRepo;

    public function __construct(RequestStack $requestStack, APIRepository $apiRepo, OfferRepository $offerRepo)
    {
        $this->apiRepo = $apiRepo;
        $this->offerRepo = $offerRepo;
        $this->session = $requestStack->getSession();
    }

    public function getCart()
    {
        $cart = $this->session->get('sessionCart', []);
        $entityCart = [];

        foreach ($cart as $itemId => $cartItem) {
            $api = $this->apiRepo->find($cartItem['apiId']);
            $offer = $this->offerRepo->find($cartItem['offerId']);

            $entityCart[] = [
                'api' => $api,
                'offer' => $offer,
            ];
        }

        return $entityCart;
    }

    public function addItem(API $api, Offer $offer)
    {
        $cart = $this->session->get('sessionCart', []);

        if (isset($cart[$api->getId()])) {
            $existingOfferId = $cart[$api->getId()]['offerId'];

            if ($existingOfferId !== $offer->getId()) {
                $cart[$api->getId()] = [
                    'apiId' => $api->getId(),
                    'offerId' => $offer->getId(),
                ];

                $this->session->set('sessionCart', $cart);

                return "Offer updated for the API.";
            }

            return "Item already added with the same offer.";
        } else {
            $cart[$api->getId()] = [
                'apiId' => $api->getId(),
                'offerId' => $offer->getId(),
            ];

            $this->session->set('sessionCart', $cart);

            return "Item added successfully";
        }
    }

    public function removeItem(API $api)
    {
        $cart = $this->session->get('sessionCart', []);
        $apiId = $api->getId();

        if (isset($cart[$apiId])) {
            unset($cart[$apiId]);
        }

        $this->session->set('sessionCart', $cart);

        return "Item removed successfully";
    }

    public function emptyCart()
    {
        $this->session->remove('sessionCart');

        return "Cart emptied successfully";
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->getCart() as $item) {
            if (isset($item['offer'])) {
                $total += $item['offer']->getPrice();
            }
        }

        return $total;
    }

}