<?php

namespace App\Entity;

use App\Repository\UserAPIKeysRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAPIKeysRepository::class)]
class UserAPIKeys
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userAPIKeys')]
    #[ORM\JoinColumn(nullable: false)]
    private ?API $API = null;

    public function getId(): ?int
    {
        return $this->id;
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
