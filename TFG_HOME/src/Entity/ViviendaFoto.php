<?php

namespace App\Entity;

use App\Repository\ViviendaFotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViviendaFotoRepository::class)]
class ViviendaFoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $foto_url = null;

    #[ORM\ManyToOne(inversedBy: 'ViviendaFotos')]
    private ?vivienda $vivienda = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFotoUrl(): ?string
    {
        return $this->foto_url;
    }

    public function setFotoUrl(string $foto_url): static
    {
        $this->foto_url = $foto_url;

        return $this;
    }

    public function getVivienda(): ?vivienda
    {
        return $this->vivienda;
    }

    public function setVivienda(?vivienda $vivienda): static
    {
        $this->vivienda = $vivienda;

        return $this;
    }
}
