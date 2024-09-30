<?php

namespace App\Entity;

use App\Repository\DependenciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DependenciaRepository::class)]
class Dependencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, Norma>
     */
    #[ORM\OneToMany(targetEntity: Norma::class, mappedBy: 'dependencia')]
    private Collection $normas;

    public function __construct()
    {
        $this->isActive = true;
        $this->normas = new ArrayCollection();
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
     * @return Collection<int, Norma>
     */
    public function getNormas(): Collection
    {
        return $this->normas;
    }

    public function addNorma(Norma $norma): static
    {
        if (!$this->normas->contains($norma)) {
            $this->normas->add($norma);
            $norma->setDependencia($this);
        }

        return $this;
    }

    public function removeNorma(Norma $norma): static
    {
        if ($this->normas->removeElement($norma)) {
            // set the owning side to null (unless already changed)
            if ($norma->getDependencia() === $this) {
                $norma->setDependencia(null);
            }
        }

        return $this;
    }
}
