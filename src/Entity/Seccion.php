<?php

namespace App\Entity;

use App\Repository\SeccionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeccionRepository::class)]
class Seccion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'seccion', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tema $tema = null;

    /**
     * @var Collection<int, SeccionNorma>
     */
    #[ORM\OneToMany(targetEntity: SeccionNorma::class, mappedBy: 'seccion')]
    private Collection $seccionNormas;

    #[ORM\Column(nullable: false)]
    private ?int $orden = null;

    #[ORM\Column(nullable: false)]
    private ?bool $isActive = null;

    public function __construct()
    {
        $this->seccionNormas = new ArrayCollection();
        $this->isActive = true;
    }

     public function getId(): ?int
    {
        return $this->id;
    }

     public function getTema(): ?Tema
     {
         return $this->tema;
     }

     public function setTema(Tema $tema): static
     {
         $this->tema = $tema;

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
             $seccionNorma->setSeccion($this);
         }

         return $this;
     }

     public function removeSeccionNorma(SeccionNorma $seccionNorma): static
     {
         if ($this->seccionNormas->removeElement($seccionNorma)) {
             // set the owning side to null (unless already changed)
             if ($seccionNorma->getSeccion() === $this) {
                 $seccionNorma->setSeccion(null);
             }
         }

         return $this;
     }

     public function getOrden(): ?int
     {
         return $this->orden;
     }

     public function setOrden(?int $orden): static
     {
         $this->orden = $orden;

         return $this;
     }

     public function isActive(): ?bool
     {
         return $this->isActive;
     }

     public function setActive(?bool $isActive): static
     {
         $this->isActive = $isActive;

         return $this;
     }

}
