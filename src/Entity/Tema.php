<?php

namespace App\Entity;

use App\Repository\TemaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemaRepository::class)]
class Tema
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, NormaTema>
     */
    #[ORM\OneToMany(targetEntity: NormaTema::class, mappedBy: 'tema')]
    private Collection $normaTemas;

    public function __construct()
    {
        $this->normaTemas = new ArrayCollection();
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
            $normaTema->setTema($this);
        }

        return $this;
    }

    public function removeNormaTema(NormaTema $normaTema): static
    {
        if ($this->normaTemas->removeElement($normaTema)) {
            // set the owning side to null (unless already changed)
            if ($normaTema->getTema() === $this) {
                $normaTema->setTema(null);
            }
        }

        return $this;
    }

}
