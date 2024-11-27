<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    private ?string $contrasena = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $apellidos = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    private ?int $puntos = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $foto = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $sesion = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(targetEntity: Vivienda::class, mappedBy: 'Usuario')]
    private Collection $viviendas;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $premium = null;

    #[ORM\OneToMany(targetEntity: Reserva::class, mappedBy: 'Usuario')]
    private Collection $vivienda;

    public function __construct()
    {
        $this->viviendas = new ArrayCollection();
        $this->vivienda = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPuntos(): ?int
    {
        return $this->puntos;
    }

    public function setPuntos(?int $puntos): self
    {
        $this->puntos = $puntos;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getSesion(): ?\DateTimeInterface
    {
        return $this->sesion;
    }

    public function setSesion(?\DateTimeInterface $sesion): self
    {
        $this->sesion = $sesion;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->contrasena;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Vivienda>
     */
    public function getViviendas(): Collection
    {
        return $this->viviendas;
    }

    public function addVivienda(Vivienda $vivienda): static
    {
        if (!$this->viviendas->contains($vivienda)) {
            $this->viviendas->add($vivienda);
            $vivienda->setUsuario($this);
        }

        return $this;
    }

    public function removeVivienda(Vivienda $vivienda): static
    {
        if ($this->viviendas->removeElement($vivienda)) {
            // set the owning side to null (unless already changed)
            if ($vivienda->getUsuario() === $this) {
                $vivienda->setUsuario(null);
            }
        }

        return $this;
    }

    public function getPremium(): ?\DateTimeInterface
    {
        return $this->premium;
    }

    public function setPremium(?\DateTimeInterface $premium): static
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * @return Collection<int, Reserva>
     */
    public function getVivienda(): Collection
    {
        return $this->vivienda;
    }

    
}
