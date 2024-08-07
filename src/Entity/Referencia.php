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

    #[ORM\ManyToOne(targetEntity: Norma::class, inversedBy: 'referenciasOrigen')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Norma $normaOrigen = null;

    #[ORM\ManyToOne(targetEntity: Norma::class, inversedBy: 'referenciasDestino')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Norma $normaDestino = null;

    #[ORM\Column(length: 100)]
    private ?string $tipoReferencia = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripcion = null;

    // Getters y setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNormaOrigen(): ?Norma
    {
        return $this->normaOrigen;
    }

    public function setNormaOrigen(?Norma $normaOrigen): self
    {
        $this->normaOrigen = $normaOrigen;

        return $this;
    }

    public function getNormaDestino(): ?Norma
    {
        return $this->normaDestino;
    }

    public function setNormaDestino(?Norma $normaDestino): self
    {
        $this->normaDestino = $normaDestino;

        return $this;
    }

    public function getTipoReferencia(): ?string
    {
        return $this->tipoReferencia;
    }

    public function setTipoReferencia(string $tipoReferencia): self
    {
        $this->tipoReferencia = $tipoReferencia;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
