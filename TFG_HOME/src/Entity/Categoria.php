<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $nombre = null;

    #[ORM\ManyToMany(targetEntity: Vivienda::class, mappedBy: 'categoria')]
    private Collection $viviendas;

    public function __construct()
    {
        $this->viviendas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

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
            $vivienda->addCategorium($this);
        }

        return $this;
    }

    public function removeVivienda(Vivienda $vivienda): static
    {
        if ($this->viviendas->removeElement($vivienda)) {
            $vivienda->removeCategorium($this);
        }

        return $this;
    }
}
