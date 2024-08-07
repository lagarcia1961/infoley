<?php

namespace App\Entity;

use App\Repository\DocumentoAdicionalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentoAdicionalRepository::class)]
class DocumentoAdicional
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Norma::class, inversedBy: 'documentosAdicionales')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Norma $norma = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $urlDocumento = null;

    // Getters y setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNorma(): ?Norma
    {
        return $this->norma;
    }

    public function setNorma(?Norma $norma): self
    {
        $this->norma = $norma;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

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

    public function getUrlDocumento(): ?string
    {
        return $this->urlDocumento;
    }

    public function setUrlDocumento(?string $urlDocumento): self
    {
        $this->urlDocumento = $urlDocumento;

        return $this;
    }
}
