<?php

namespace App\Entity;

use App\Repository\NormaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NormaRepository::class)]
class Norma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TipoNorma::class, inversedBy: 'normas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipoNorma $tipoNorma = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column]
    private ?int $anio = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $fechaPublicacion = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $textoCompleto = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $urlPdf = null;

    #[ORM\OneToMany(mappedBy: 'normaOrigen', targetEntity: Referencia::class)]
    private Collection $referenciasOrigen;

    #[ORM\OneToMany(mappedBy: 'normaDestino', targetEntity: Referencia::class)]
    private Collection $referenciasDestino;

    #[ORM\OneToMany(mappedBy: 'norma', targetEntity: DocumentoAdicional::class)]
    private Collection $documentosAdicionales;

    /**
     * @var Collection<int, NormaTema>
     */
    #[ORM\OneToMany(targetEntity: NormaTema::class, mappedBy: 'norma')]
    private Collection $normaTemas;

    #[ORM\Column(options: ["default" => 1])]
    private ?bool $isActive = null;

    #[ORM\OneToOne(targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $normaOrigen = null;

    #[ORM\OneToOne(targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $normaDestino = null;
  
    public function __construct()
    {
        $this->referenciasOrigen = new ArrayCollection();
        $this->referenciasDestino = new ArrayCollection();
        $this->documentosAdicionales = new ArrayCollection();
        $this->normaTemas = new ArrayCollection();
        $this->isActive = true;
    }

    // Getters y setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoNorma(): ?TipoNorma
    {
        return $this->tipoNorma;
    }

    public function setTipoNorma(?TipoNorma $tipoNorma): self
    {
        $this->tipoNorma = $tipoNorma;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getAnio(): ?int
    {
        return $this->anio;
    }

    public function setAnio(int $anio): self
    {
        $this->anio = $anio;

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

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(?\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getTextoCompleto(): ?string
    {
        return $this->textoCompleto;
    }

    public function setTextoCompleto(?string $textoCompleto): self
    {
        $this->textoCompleto = $textoCompleto;

        return $this;
    }

    public function getUrlPdf(): ?string
    {
        return $this->urlPdf;
    }

    public function setUrlPdf(?string $urlPdf): self
    {
        $this->urlPdf = $urlPdf;

        return $this;
    }

    /**
     * @return Collection<int, Referencia>
     */
    public function getReferenciasOrigen(): Collection
    {
        return $this->referenciasOrigen;
    }

    public function addReferenciasOrigen(Referencia $referencia): self
    {
        if (!$this->referenciasOrigen->contains($referencia)) {
            $this->referenciasOrigen->add($referencia);
            $referencia->setNormaOrigen($this);
        }

        return $this;
    }

    public function removeReferenciasOrigen(Referencia $referencia): self
    {
        if ($this->referenciasOrigen->removeElement($referencia)) {
            // set the owning side to null (unless already changed)
            if ($referencia->getNormaOrigen() === $this) {
                $referencia->setNormaOrigen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Referencia>
     */
    public function getReferenciasDestino(): Collection
    {
        return $this->referenciasDestino;
    }

    public function addReferenciasDestino(Referencia $referencia): self
    {
        if (!$this->referenciasDestino->contains($referencia)) {
            $this->referenciasDestino->add($referencia);
            $referencia->setNormaDestino($this);
        }

        return $this;
    }

    public function removeReferenciasDestino(Referencia $referencia): self
    {
        if ($this->referenciasDestino->removeElement($referencia)) {
            // set the owning side to null (unless already changed)
            if ($referencia->getNormaDestino() === $this) {
                $referencia->setNormaDestino(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DocumentoAdicional>
     */
    public function getDocumentosAdicionales(): Collection
    {
        return $this->documentosAdicionales;
    }

    public function addDocumentoAdicional(DocumentoAdicional $documentoAdicional): self
    {
        if (!$this->documentosAdicionales->contains($documentoAdicional)) {
            $this->documentosAdicionales->add($documentoAdicional);
            $documentoAdicional->setNorma($this);
        }

        return $this;
    }

    public function removeDocumentoAdicional(DocumentoAdicional $documentoAdicional): self
    {
        if ($this->documentosAdicionales->removeElement($documentoAdicional)) {
            // set the owning side to null (unless already changed)
            if ($documentoAdicional->getNorma() === $this) {
                $documentoAdicional->setNorma(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NormaTema>
     */
    public function getNormaTemas(): Collection
    {
        return $this->normaTemas;
    }

    public function addNormaTema(NormaTema $normaTema): static
    {
        if (!$this->normaTemas->contains($normaTema)) {
            $this->normaTemas->add($normaTema);
            $normaTema->setNorma($this);
        }

        return $this;
    }

    public function removeNormaTema(NormaTema $normaTema): static
    {
        if ($this->normaTemas->removeElement($normaTema)) {
            // set the owning side to null (unless already changed)
            if ($normaTema->getNorma() === $this) {
                $normaTema->setNorma(null);
            }
        }

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

    public function getNormaOrigen(): ?self
    {
        return $this->normaOrigen;
    }

    public function setNormaOrigen(?self $normaOrigen): static
    {
        $this->normaOrigen = $normaOrigen;

        return $this;
    }

    public function getNormaDestino(): ?self
    {
        return $this->normaDestino;
    }

    public function setNormaDestino(?self $normaDestino): static
    {
        $this->normaDestino = $normaDestino;

        return $this;
    }
}
