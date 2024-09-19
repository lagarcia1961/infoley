<?php

namespace App\Entity;

use App\Repository\ReferenciaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReferenciaRepository::class)]
class Referencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'referencias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipoReferencia $tipoReferencia = null;

    #[ORM\ManyToOne(inversedBy: 'normasOrigen')]
    private ?Norma $normaOrigen = null;

    #[ORM\ManyToOne(inversedBy: 'normasDestino')]
    private ?Norma $normaDestino = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoReferencia(): ?TipoReferencia
    {
        return $this->tipoReferencia;
    }

    public function setTipoReferencia(?TipoReferencia $tipoReferencia): static
    {
        $this->tipoReferencia = $tipoReferencia;

        return $this;
    }

    public function getNormaOrigen(): ?Norma
    {
        return $this->normaOrigen;
    }

    public function setNormaOrigen(?Norma $normaOrigen): static
    {
        $this->normaOrigen = $normaOrigen;

        return $this;
    }

    public function getNormaDestino(): ?Norma
    {
        return $this->normaDestino;
    }

    public function setNormaDestino(?Norma $normaDestino): static
    {
        $this->normaDestino = $normaDestino;

        return $this;
    }
}
