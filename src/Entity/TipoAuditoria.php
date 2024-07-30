<?php

namespace App\Entity;

use App\Repository\TipoAuditoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoAuditoriaRepository::class)]
class TipoAuditoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $descripcion = null;

    /**
     * @var Collection<int, Auditoria>
     */
    #[ORM\OneToMany(targetEntity: Auditoria::class, mappedBy: 'tipoAuditoria')]
    private Collection $auditorias;

    public function __construct()
    {
        $this->auditorias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection<int, Auditoria>
     */
    public function getAuditorias(): Collection
    {
        return $this->auditorias;
    }

    public function addAuditoria(Auditoria $auditoria): static
    {
        if (!$this->auditorias->contains($auditoria)) {
            $this->auditorias->add($auditoria);
            $auditoria->setTipoAuditoria($this);
        }

        return $this;
    }

    public function removeAuditoria(Auditoria $auditoria): static
    {
        if ($this->auditorias->removeElement($auditoria)) {
            // set the owning side to null (unless already changed)
            if ($auditoria->getTipoAuditoria() === $this) {
                $auditoria->setTipoAuditoria(null);
            }
        }

        return $this;
    }
}
