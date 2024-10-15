<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, API>
     */
    #[ORM\OneToMany(targetEntity: API::class, mappedBy: 'createdBy', orphanRemoval: true)]
    private Collection $registeredAPIs;

    /**
     * @var Collection<int, UserAPIKey>
     */
    #[ORM\OneToMany(targetEntity: UserAPIKey::class, mappedBy: 'ofUser', orphanRemoval: true)]
    private Collection $boughtAPIKeys;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'byUser', orphanRemoval: true)]
    private Collection $orders;

    public function __construct()
    {
        $this->registeredAPIs = new ArrayCollection();
        $this->boughtAPIKeys = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, API>
     */
    public function getRegisteredAPIs(): Collection
    {
        return $this->registeredAPIs;
    }

    public function addRegisteredAPI(API $registeredAPI): static
    {
        if (!$this->registeredAPIs->contains($registeredAPI)) {
            $this->registeredAPIs->add($registeredAPI);
            $registeredAPI->setCreatedBy($this);
        }

        return $this;
    }

    public function removeRegisteredAPI(API $registeredAPI): static
    {
        if ($this->registeredAPIs->removeElement($registeredAPI)) {
            // set the owning side to null (unless already changed)
            if ($registeredAPI->getCreatedBy() === $this) {
                $registeredAPI->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAPIKey>
     */
    public function getBoughtAPIKeys(): Collection
    {
        return $this->boughtAPIKeys;
    }

    public function addBoughtAPIKey(UserAPIKey $boughtAPIKey): static
    {
        if (!$this->boughtAPIKeys->contains($boughtAPIKey)) {
            $this->boughtAPIKeys->add($boughtAPIKey);
            $boughtAPIKey->setOfUser($this);
        }

        return $this;
    }

    public function removeBoughtAPIKey(UserAPIKey $boughtAPIKey): static
    {
        if ($this->boughtAPIKeys->removeElement($boughtAPIKey)) {
            // set the owning side to null (unless already changed)
            if ($boughtAPIKey->getOfUser() === $this) {
                $boughtAPIKey->setOfUser(null);
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
            $order->setByUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getByUser() === $this) {
                $order->setByUser(null);
            }
        }

        return $this;
    }
}
