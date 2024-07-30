<?php

namespace App\Entity;

use App\Repository\AuditoriaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuditoriaRepository::class)]
class Auditoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'auditorias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?tipoAuditoria $tipoAuditoria = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoAuditoria(): ?tipoAuditoria
    {
        return $this->tipoAuditoria;
    }

    public function setTipoAuditoria(?tipoAuditoria $tipoAuditoria): static
    {
        $this->tipoAuditoria = $tipoAuditoria;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
