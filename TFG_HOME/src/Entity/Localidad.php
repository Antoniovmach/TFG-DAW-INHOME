<?php

namespace App\Entity;

use App\Repository\LocalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalidadRepository::class)]
class Localidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\Column(length: 30)]
    private ?string $NOMBRE = null;

  

    #[ORM\ManyToOne(inversedBy: 'Localidads')]
    private ?Provincia $Provincia = null;

    #[ORM\OneToMany(targetEntity: Vivienda::class, mappedBy: 'Localidad')]
    private Collection $viviendas;

    public function __construct()
    {
        $this->viviendas = new ArrayCollection();
    }

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNOMBRE(): ?string
    {
        return $this->NOMBRE;
    }

     public function setNOMBRE(string $NOMBRE): static
     {
         $this->NOMBRE = $NOMBRE;

         return $this;
     }

 
    public function getProvincia(): ?Provincia
    {
        return $this->Provincia;
    }

    public function setProvincia(?Provincia $Provincia): static
    {
        $this->Provincia = $Provincia;

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
            $vivienda->setLocalidad($this);
        }

        return $this;
    }

    public function removeVivienda(Vivienda $vivienda): static
    {
        if ($this->viviendas->removeElement($vivienda)) {
            // set the owning side to null (unless already changed)
            if ($vivienda->getLocalidad() === $this) {
                $vivienda->setLocalidad(null);
            }
        }

        return $this;
    }

 
}
