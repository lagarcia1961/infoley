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
    #[ORM\JoinColumn(nullable: true)]
    private ?TipoAuditoria $tipoAuditoria = null;

    #[ORM\ManyToOne(inversedBy: 'auditorias')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    private ?string $entidad = null;

    #[ORM\Column(nullable: true)]
    private ?array $registroAnterior = null;

    #[ORM\Column(nullable: true)]
    private ?array $registroNuevo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $entidadId = null;

    public function __construct()
    {
        $this->fecha = new \DateTime(); // Establece la fecha y hora actual
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoAuditoria(): ?TipoAuditoria
    {
        return $this->tipoAuditoria;
    }

    public function setTipoAuditoria(?TipoAuditoria $tipoAuditoria): static
    {
        $this->tipoAuditoria = $tipoAuditoria;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEntidad(): ?string
    {
        return $this->entidad;
    }

    public function setEntidad(string $entidad): static
    {
        $this->entidad = $entidad;

        return $this;
    }

    public function getRegistroAnterior(): ?array
    {
        return $this->registroAnterior;
    }

    public function setRegistroAnterior(?array $registroAnterior): static
    {
        $this->registroAnterior = $registroAnterior;

        return $this;
    }

    public function getRegistroNuevo(): ?array
    {
        return $this->registroNuevo;
    }

    public function setRegistroNuevo(?array $registroNuevo): static
    {
        $this->registroNuevo = $registroNuevo;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEntidadId(): ?int
    {
        return $this->entidadId;
    }

    public function setEntidadId(int $entidadId): static
    {
        $this->entidadId = $entidadId;

        return $this;
    }

    public function getData(): array
    {
        return [
            'id' => $this->getId(),
            'fecha' => $this->getFecha()->format('d/m/Y H:i:s'),
            'accion' => $this->getTipoAuditoria()->getDescripcion(),
            'usuario_nombre' => $this->getUser()->getNombre(),
            'usuario_email' => $this->getUser()->getEmail(),
            'usuario_usuario' => $this->getUser()->getUsuario()
        ];
    }
}
