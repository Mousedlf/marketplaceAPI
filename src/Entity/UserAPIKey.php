<?php

namespace App\Entity;

use App\Repository\UserAPIKeyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAPIKeyRepository::class)]
class UserAPIKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?int $nbUsedRequests = null;

    #[ORM\Column]
    private ?int $nbPaidRequests = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToOne(inversedBy: 'boughtAPIKeys')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ofUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbUsedRequests(): ?int
    {
        return $this->nbUsedRequests;
    }

    public function setNbUsedRequests(int $nbUsedRequests): static
    {
        $this->nbUsedRequests = $nbUsedRequests;

        return $this;
    }

    public function getNbPaidRequests(): ?int
    {
        return $this->nbPaidRequests;
    }

    public function setNbPaidRequests(int $nbPaidRequests): static
    {
        $this->nbPaidRequests = $nbPaidRequests;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getOfUser(): ?User
    {
        return $this->ofUser;
    }

    public function setOfUser(?User $ofUser): static
    {
        $this->ofUser = $ofUser;

        return $this;
    }
}
