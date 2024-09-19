<?php

namespace App\Entity;

use App\Repository\TipoReferenciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoReferenciaRepository::class)]
class TipoReferencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(options: ["default" => 1])]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, Referencia>
     */
    #[ORM\OneToMany(targetEntity: Referencia::class, mappedBy: 'tipoReferencia')]
    private Collection $referencias;

    public function __construct()
    {
        $this->isActive = true;
        $this->referencias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

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
    public function getReferencias(): Collection
    {
        return $this->referencias;
    }

    public function addReferencia(Referencia $referencia): static
    {
        if (!$this->referencias->contains($referencia)) {
            $this->referencias->add($referencia);
            $referencia->setTipoReferencia($this);
        }

        return $this;
    }

    public function removeReferencia(Referencia $referencia): static
    {
        if ($this->referencias->removeElement($referencia)) {
            // set the owning side to null (unless already changed)
            if ($referencia->getTipoReferencia() === $this) {
                $referencia->setTipoReferencia(null);
            }
        }

        return $this;
    }
}
