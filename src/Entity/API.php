<?php

namespace App\Entity;

use App\Repository\APIRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    public function __construct()
    {
        $this->userAPIKeys = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
}
