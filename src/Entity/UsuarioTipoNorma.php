<?php

namespace App\Entity;

use App\Repository\UsuarioTipoNormaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioTipoNormaRepository::class)]
class UsuarioTipoNorma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'usuarioTipoNormas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'usuarioTipoNormas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipoNorma $TipoNorma = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTipoNorma(): ?TipoNorma
    {
        return $this->TipoNorma;
    }

    public function setTipoNorma(?TipoNorma $TipoNorma): static
    {
        $this->TipoNorma = $TipoNorma;

        return $this;
    }
}
