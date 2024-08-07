<?php

namespace App\Entity;

use App\Repository\TipoNormaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoNormaRepository::class)]
class TipoNorma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'tipoNorma', targetEntity: Norma::class)]
    private Collection $normas;

    public function __construct()
    {
        $this->normas = new ArrayCollection();
    }

    // Getters y setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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

    /**
     * @return Collection<int, Norma>
     */
    public function getNormas(): Collection
    {
        return $this->normas;
    }

    public function addNorma(Norma $norma): self
    {
        if (!$this->normas->contains($norma)) {
            $this->normas->add($norma);
            $norma->setTipoNorma($this);
        }

        return $this;
    }

    public function removeNorma(Norma $norma): self
    {
        if ($this->normas->removeElement($norma)) {
            // set the owning side to null (unless already changed)
            if ($norma->getTipoNorma() === $this) {
                $norma->setTipoNorma(null);
            }
        }

        return $this;
    }
}
