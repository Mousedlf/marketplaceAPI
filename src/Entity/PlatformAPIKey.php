<?php

namespace App\Entity;

use App\Repository\PlatformAPIKeyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatformAPIKeyRepository::class)]
class PlatformAPIKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 300)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'platformAPIKeys')]
    #[ORM\JoinColumn(nullable: false)]
    private ?API $api = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getApi(): ?API
    {
        return $this->api;
    }

    public function setApi(?API $api): static
    {
        $this->api = $api;

        return $this;
    }
}
