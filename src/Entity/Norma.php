<?php

namespace App\Entity;

use App\Repository\NormaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints\Uuid;

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

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $fechaSancion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titulo = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $fechaPublicacion = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $textoCompleto = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $urlPdf = null;

    #[ORM\OneToMany(mappedBy: 'norma', targetEntity: DocumentoAdicional::class)]
    private Collection $documentosAdicionales;

    /**
     * @var Collection<int, NormaTema>
     */
    #[ORM\OneToMany(targetEntity: NormaTema::class, mappedBy: 'norma')]
    private Collection $normaTemas;

    #[ORM\Column(options: ["default" => 1])]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, Referencia>
     */
    #[ORM\OneToMany(targetEntity: Referencia::class, mappedBy: 'normaOrigen')]
    private Collection $normasOrigen;

    /**
     * @var Collection<int, Referencia>
     */
    #[ORM\OneToMany(targetEntity: Referencia::class, mappedBy: 'normaDestino')]
    private Collection $normasDestino;

    #[ORM\ManyToOne(inversedBy: 'normas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dependencia $dependencia = null;

    #[ORM\Column(length: 600, nullable: false, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $textoCompletoHtml = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $textoCompletoModificadoHtml = null;

    /**
     * @var Collection<int, SeccionNorma>
     */
    #[ORM\OneToMany(targetEntity: SeccionNorma::class, mappedBy: 'norma')]
    private Collection $seccionNormas;

    #[ORM\Column(nullable: true)]
    private ?bool $modificado = null;

    public function __construct()
    {
        $this->documentosAdicionales = new ArrayCollection();
        $this->normaTemas = new ArrayCollection();
        $this->isActive = true;
        $this->normasOrigen = new ArrayCollection();
        $this->normasDestino = new ArrayCollection();
        $this->seccionNormas = new ArrayCollection();
        $this->modificado = false;
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
        $this->setSlug();
        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;
        $this->setSlug();
        return $this;
    }

    public function getFechaSancion(): ?\DateTimeInterface
    {
        return $this->fechaSancion;
    }

    public function setFechaSancion(?\DateTimeInterface $fechaSancion): self
    {
        $this->fechaSancion = $fechaSancion;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;
        $this->setSlug();
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

    /**
     * @return Collection<int, Referencia>
     */
    public function getNormasOrigen(): Collection
    {
        return $this->normasOrigen;
    }

    public function addNormasOrigen(Referencia $normasOrigen): static
    {
        if (!$this->normasOrigen->contains($normasOrigen)) {
            $this->normasOrigen->add($normasOrigen);
            $normasOrigen->setNormaOrigen($this);
        }

        return $this;
    }

    public function removeNormasOrigen(Referencia $normasOrigen): static
    {
        if ($this->normasOrigen->removeElement($normasOrigen)) {
            // set the owning side to null (unless already changed)
            if ($normasOrigen->getNormaOrigen() === $this) {
                $normasOrigen->setNormaOrigen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Referencia>
     */
    public function getNormasDestino(): Collection
    {
        return $this->normasDestino;
    }

    public function addNormasDestino(Referencia $normasDestino): static
    {
        if (!$this->normasDestino->contains($normasDestino)) {
            $this->normasDestino->add($normasDestino);
            $normasDestino->setNormaDestino($this);
        }

        return $this;
    }

    public function removeNormasDestino(Referencia $normasDestino): static
    {
        if ($this->normasDestino->removeElement($normasDestino)) {
            // set the owning side to null (unless already changed)
            if ($normasDestino->getNormaDestino() === $this) {
                $normasDestino->setNormaDestino(null);
            }
        }

        return $this;
    }

    public function getDependencia(): ?Dependencia
    {
        return $this->dependencia;
    }

    public function setDependencia(?Dependencia $dependencia): static
    {
        $this->dependencia = $dependencia;

        return $this;
    }

    public function getNormaBasicData(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
        ];
    }

    public function getNormaFullData(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'numero' => $this->numero,
            'fechaSancion' => $this->fechaSancion ? $this->fechaSancion->format('Y') : '',
            'fechaPublicacion' => $this->fechaPublicacion->format('d/m/Y'),
            'textoCompleto' => $this->textoCompleto,
            'textoCompletoHtml' => $this->textoCompletoHtml,
            'textoCompletoModificadoHtml' => $this->textoCompletoModificadoHtml,
            'urlPdf' => $this->urlPdf,
            'tipoNorma' => $this->tipoNorma->getNombre()
        ];
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(): static
    {
        $slugify = new Slugify();

        if ($this->tipoNorma !== null && $this->titulo !== null && $this->numero !== null) {
            $this->slug = $slugify->slugify(
                $this->tipoNorma->getNombre() . '-' . $this->titulo . '-' . $this->numero . '-' . rand(0, 99999)
            );
        }

        return $this;
    }

    public function getTextoCompletoHtml(): ?string
    {
        return $this->textoCompletoHtml;
    }

    public function setTextoCompletoHtml(?string $textoCompletoHtml): static
    {
        $this->textoCompletoHtml = $textoCompletoHtml;

        return $this;
    }

    public function getTextoCompletoModificadoHtml(): ?string
    {
        return $this->textoCompletoModificadoHtml;
    }

    public function setTextoCompletoModificadoHtml(?string $textoCompletoModificadoHtml): static
    {
        $this->textoCompletoModificadoHtml = $textoCompletoModificadoHtml;

        return $this;
    }

    /**
     * @return Collection<int, SeccionNorma>
     */
    public function getSeccionNormas(): Collection
    {
        return $this->seccionNormas;
    }

    public function addSeccionNorma(SeccionNorma $seccionNorma): static
    {
        if (!$this->seccionNormas->contains($seccionNorma)) {
            $this->seccionNormas->add($seccionNorma);
            $seccionNorma->setNorma($this);
        }

        return $this;
    }

    public function removeSeccionNorma(SeccionNorma $seccionNorma): static
    {
        if ($this->seccionNormas->removeElement($seccionNorma)) {
            // set the owning side to null (unless already changed)
            if ($seccionNorma->getNorma() === $this) {
                $seccionNorma->setNorma(null);
            }
        }

        return $this;
    }

    public function isModificado(): ?bool
    {
        return $this->modificado;
    }

    public function setModificado(?bool $modificado): static
    {
        $this->modificado = $modificado;

        return $this;
    }
}
