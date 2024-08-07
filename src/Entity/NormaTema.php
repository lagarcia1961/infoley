<?php

namespace App\Entity;

use App\Repository\NormaTemaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NormaTemaRepository::class)]
class NormaTema
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'normaTemas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tema $tema = null;

    #[ORM\ManyToOne(inversedBy: 'normaTemas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Norma $norma = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTema(): ?Tema
    {
        return $this->tema;
    }

    public function setTema(?Tema $tema): static
    {
        $this->tema = $tema;

        return $this;
    }

    public function getNorma(): ?Norma
    {
        return $this->norma;
    }

    public function setNorma(?Norma $norma): static
    {
        $this->norma = $norma;

        return $this;
    }
}
