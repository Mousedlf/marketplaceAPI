<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $nbOfAvailableRequests = null;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    private ?API $API = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getNbOfAvailableRequests(): ?int
    {
        return $this->nbOfAvailableRequests;
    }

    public function setNbOfAvailableRequests(int $nbOfAvailableRequests): static
    {
        $this->nbOfAvailableRequests = $nbOfAvailableRequests;

        return $this;
    }

    public function getAPI(): ?API
    {
        return $this->API;
    }

    public function setAPI(?API $API): static
    {
        $this->API = $API;

        return $this;
    }
}
