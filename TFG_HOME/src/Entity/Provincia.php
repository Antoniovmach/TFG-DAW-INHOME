<?php

namespace App\Entity;

use App\Repository\ProvinciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProvinciaRepository::class)]
class Provincia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $NOMBRE = null;

    #[ORM\OneToMany(targetEntity: Localidad::class, mappedBy: 'Provincia')]
    private Collection $Localidads;

    public function __construct()
    {
        $this->Localidads = new ArrayCollection();
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

    /**
     * @return Collection<int, Localidad>
     */
    public function getLocalidads(): Collection
    {
        return $this->Localidads;
    }

    public function addLocalidad(Localidad $Localidad): static
    {
        if (!$this->Localidads->contains($Localidad)) {
            $this->Localidads->add($Localidad);
            $Localidad->setProvincia($this);
        }

        return $this;
    }

    public function removeLocalidad(Localidad $Localidad): static
    {
        if ($this->Localidads->removeElement($Localidad)) {
            // set the owning side to null (unless already changed)
            if ($Localidad->getProvincia() === $this) {
                $Localidad->setProvincia(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->NOMBRE;
    }
}
