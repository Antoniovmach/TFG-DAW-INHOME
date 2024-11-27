<?php

namespace App\Entity;

use App\Repository\DisponibilidadViviendaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisponibilidadViviendaRepository::class)]
class DisponibilidadVivienda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(type: 'integer')]
    private ?int $precio = null;

    #[ORM\ManyToOne(targetEntity: Vivienda::class)]
    private ?Vivienda $vivienda = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getVivienda(): ?Vivienda
    {
        return $this->vivienda;
    }

    public function setVivienda(?Vivienda $vivienda): self
    {
        $this->vivienda = $vivienda;

        return $this;
    }
}
