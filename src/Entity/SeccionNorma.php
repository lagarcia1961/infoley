<?php

namespace App\Entity;

use App\Repository\SeccionNormaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeccionNormaRepository::class)]
class SeccionNorma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seccionNormas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?seccion $seccion = null;

    #[ORM\ManyToOne(inversedBy: 'seccionNormas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?norma $norma = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeccion(): ?seccion
    {
        return $this->seccion;
    }

    public function setSeccion(?seccion $seccion): static
    {
        $this->seccion = $seccion;

        return $this;
    }

    public function getNorma(): ?norma
    {
        return $this->norma;
    }

    public function setNorma(?norma $norma): static
    {
        $this->norma = $norma;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
