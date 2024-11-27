<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'vivienda')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $Usuario = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?DisponibilidadVivienda $disponibilidad_vivienda = null;

    #[ORM\Column]
    private ?bool $confirmado = null;

    #[ORM\Column(nullable: true)]
    private ?array $intercambiojson = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->Usuario;
    }

    public function setUsuario(?Usuario $Usuario): static
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    public function getDisponibilidadVivienda(): ?DisponibilidadVivienda
    {
        return $this->disponibilidad_vivienda;
    }

    public function setDisponibilidadVivienda(DisponibilidadVivienda $disponibilidad_vivienda): static
    {
        $this->disponibilidad_vivienda = $disponibilidad_vivienda;

        return $this;
    }

    public function isConfirmado(): ?bool
    {
        return $this->confirmado;
    }

    public function setConfirmado(bool $confirmado): static
    {
        $this->confirmado = $confirmado;

        return $this;
    }

    public function getIntercambiojson(): ?array
    {
        return $this->intercambiojson;
    }

    public function setIntercambiojson(?array $intercambiojson): static
    {
        $this->intercambiojson = $intercambiojson;

        return $this;
    }
}
