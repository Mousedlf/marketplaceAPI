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

    #[ORM\Column(length: 500)]
    private ?string $clientCreationRoute = null;

    #[ORM\Column(length: 500)]
    private ?string $baseUrl = null;

    #[ORM\Column(length: 500)]
    private ?string $getRequestsRoute = null;

    #[ORM\Column(length: 500)]
    private ?string $revokeKeyRoute = null;

    #[ORM\Column(length: 500)]
    private ?string $generateNewKey = null;

    #[ORM\Column(length: 500)]
    private ?string $addNewRequestsRoute = null;

    /**
     * @var Collection<int, PlatformAPIKey>
     */
    #[ORM\OneToMany(targetEntity: PlatformAPIKey::class, mappedBy: 'api')]
    private Collection $platformAPIKeys;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->platformAPIKeys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClientCreationRoute(): ?string
    {
        return $this->clientCreationRoute;
    }

    public function setClientCreationRoute(string $clientCreationRoute): static
    {
        $this->clientCreationRoute = $clientCreationRoute;

        return $this;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): static
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function getGetRequestsRoute(): ?string
    {
        return $this->getRequestsRoute;
    }

    public function setGetRequestsRoute(string $getRequestsRoute): static
    {
        $this->getRequestsRoute = $getRequestsRoute;

        return $this;
    }

    public function getRevokeKeyRoute(): ?string
    {
        return $this->revokeKeyRoute;
    }

    public function setRevokeKeyRoute(string $revokeKeyRoute): static
    {
        $this->revokeKeyRoute = $revokeKeyRoute;

        return $this;
    }

    public function getGenerateNewKey(): ?string
    {
        return $this->generateNewKey;
    }

    public function setGenerateNewKey(string $generateNewKey): static
    {
        $this->generateNewKey = $generateNewKey;

        return $this;
    }

    public function getAddNewRequestsRoute(): ?string
    {
        return $this->addNewRequestsRoute;
    }

    public function setAddNewRequestsRoute(string $addNewRequestsRoute): static
    {
        $this->addNewRequestsRoute = $addNewRequestsRoute;

        return $this;
    }

    /**
     * @return Collection<int, PlatformAPIKey>
     */
    public function getPlatformAPIKeys(): Collection
    {
        return $this->platformAPIKeys;
    }

    public function addPlatformAPIKey(PlatformAPIKey $platformAPIKey): static
    {
        if (!$this->platformAPIKeys->contains($platformAPIKey)) {
            $this->platformAPIKeys->add($platformAPIKey);
            $platformAPIKey->setApi($this);
        }

        return $this;
    }

    public function removePlatformAPIKey(PlatformAPIKey $platformAPIKey): static
    {
        if ($this->platformAPIKeys->removeElement($platformAPIKey)) {
            // set the owning side to null (unless already changed)
            if ($platformAPIKey->getApi() === $this) {
                $platformAPIKey->setApi(null);
            }
        }

        return $this;
    }
}
