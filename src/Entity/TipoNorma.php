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

    #[ORM\OneToMany(mappedBy: 'tipoNorma', targetEntity: Norma::class)]
    private Collection $normas;

    /**
     * @var Collection<int, UsuarioTipoNorma>
     */
    #[ORM\OneToMany(targetEntity: UsuarioTipoNorma::class, mappedBy: 'TipoNorma')]
    private Collection $usuarioTipoNormas;

    #[ORM\Column(options: ["default" => 1])]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?int $rango = null;

    public function __construct()
    {
        $this->normas = new ArrayCollection();
        $this->usuarioTipoNormas = new ArrayCollection();
        $this->isActive = true;
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

    /**
     * @return Collection<int, UsuarioTipoNorma>
     */
    public function getUsuarioTipoNormas(): Collection
    {
        return $this->usuarioTipoNormas;
    }

    public function addUsuarioTipoNorma(UsuarioTipoNorma $usuarioTipoNorma): static
    {
        if (!$this->usuarioTipoNormas->contains($usuarioTipoNorma)) {
            $this->usuarioTipoNormas->add($usuarioTipoNorma);
            $usuarioTipoNorma->setTipoNorma($this);
        }

        return $this;
    }

    public function removeUsuarioTipoNorma(UsuarioTipoNorma $usuarioTipoNorma): static
    {
        if ($this->usuarioTipoNormas->removeElement($usuarioTipoNorma)) {
            // set the owning side to null (unless already changed)
            if ($usuarioTipoNorma->getTipoNorma() === $this) {
                $usuarioTipoNorma->setTipoNorma(null);
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

    public function getRango(): ?int
    {
        return $this->rango;
    }

    public function setRango(int $rango): static
    {
        $this->rango = $rango;

        return $this;
    }
}
