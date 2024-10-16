<?php

namespace App\Entity;

use App\Repository\APIRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: APIRepository::class)]
class API
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'APIs')]
    private Collection $orders;

    /**
     * @var Collection<int, UserAPIKeys>
     */
    #[ORM\OneToMany(targetEntity: UserAPIKeys::class, mappedBy: 'API', orphanRemoval: true)]
    private Collection $userAPIKeys;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'registeredAPIs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'API')]
    private Collection $offers;

    public function __construct()
    {
        $this->userAPIKeys = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, UserAPIKey>
     */
    public function getUserAPIKeys(): Collection
    {
        return $this->userAPIKeys;
    }

    public function addUserAPIKey(UserAPIKey $userAPIKey): static
    {
        if (!$this->userAPIKeys->contains($userAPIKey)) {
            $this->userAPIKeys->add($userAPIKey);
            $userAPIKey->setApi($this);
        }

        return $this;
    }

    public function removeUserAPIKey(UserAPIKey $userAPIKey): static
    {
        if ($this->userAPIKeys->removeElement($userAPIKey)) {
            // set the owning side to null (unless already changed)
            if ($userAPIKey->getApi() === $this) {
                $userAPIKey->setApi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->addAPI($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            $order->removeAPI($this);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setAPI($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getAPI() === $this) {
                $offer->setAPI(null);
            }
        }

        return $this;
    }
}
