<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email','usuario'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Auditoria>
     */
    #[ORM\OneToMany(targetEntity: Auditoria::class, mappedBy: 'user')]
    private Collection $auditorias;

    /**
     * @var Collection<int, UsuarioTipoNorma>
     */
    #[ORM\OneToMany(targetEntity: UsuarioTipoNorma::class, mappedBy: 'user')]
    private Collection $usuarioTipoNormas;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(length: 100)]
    private ?string $usuario = null;

    public function __construct()
    {
        $this->auditorias = new ArrayCollection();
        $this->usuarioTipoNormas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $auditoria->setUser($this);
        }

        return $this;
    }

    public function removeAuditoria(Auditoria $auditoria): static
    {
        if ($this->auditorias->removeElement($auditoria)) {
            // set the owning side to null (unless already changed)
            if ($auditoria->getUser() === $this) {
                $auditoria->setUser(null);
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
            $usuarioTipoNorma->setUser($this);
        }

        return $this;
    }

    public function removeUsuarioTipoNorma(UsuarioTipoNorma $usuarioTipoNorma): static
    {
        if ($this->usuarioTipoNormas->removeElement($usuarioTipoNorma)) {
            // set the owning side to null (unless already changed)
            if ($usuarioTipoNorma->getUser() === $this) {
                $usuarioTipoNorma->setUser(null);
            }
        }

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(?string $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }
}
