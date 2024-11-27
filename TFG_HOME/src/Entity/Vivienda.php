<?php

namespace App\Entity;

use App\Repository\ViviendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViviendaRepository::class)]
class Vivienda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $TITULO = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DESCRIPCION = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $npersonas = null;

    #[ORM\Column(nullable: true)]
    private ?array $LOCALIZACION = null;

    #[ORM\ManyToMany(targetEntity: Categoria::class, inversedBy: 'viviendas')]
    private Collection $categoria;

    #[ORM\ManyToOne(inversedBy: 'viviendas')]
    private ?Usuario $Usuario = null;

    #[ORM\ManyToOne(inversedBy: 'viviendas')]
    private ?Localidad $Localidad = null;

    #[ORM\OneToMany(targetEntity: ViviendaFoto::class, mappedBy: 'vivienda')]
    private Collection $ViviendaFotos;



   
 

    public function __construct()
    {
        $this->categoria = new ArrayCollection();
        $this->ViviendaFotos = new ArrayCollection();
   
      
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTITULO(): ?string
    {
        return $this->TITULO;
    }

    public function setTITULO(string $TITULO): static
    {
        $this->TITULO = $TITULO;

        return $this;
    }

    public function getDESCRIPCION(): ?string
    {
        return $this->DESCRIPCION;
    }

    public function setDESCRIPCION(?string $DESCRIPCION): static
    {
        $this->DESCRIPCION = $DESCRIPCION;

        return $this;
    }

    public function getnpersonas(): ?string
    {
        return $this->npersonas;
    }

    public function setnpersonas(?string $npersonas): static
    {
        $this->npersonas = $npersonas;

        return $this;
    }

    public function getLOCALIZACION(): ?array
    {
        return $this->LOCALIZACION;
    }

    public function setLOCALIZACION(?array $LOCALIZACION): static
    {
        $this->LOCALIZACION = $LOCALIZACION;

        return $this;
    }

    /**
     * @return Collection<int, Categoria>
     */
    public function getCategoria(): Collection
    {
        return $this->categoria;
    }

    public function addCategorium(Categoria $categorium): static
    {
        if (!$this->categoria->contains($categorium)) {
            $this->categoria->add($categorium);
        }

        return $this;
    }

    public function removeCategorium(Categoria $categorium): static
    {
        $this->categoria->removeElement($categorium);

        return $this;
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

    /**
     * @return Collection<int, Localidad>
     */

    public function getLocalidad(): ?Localidad
    {
        return $this->Localidad;
    }

    public function setLocalidad(?Localidad $Localidad): static
    {
        $this->Localidad = $Localidad;

        return $this;
    }

    public function __toString(): string
    {
        return json_encode($this->getLOCALIZACION());
    }
    

    /**
     * @return Collection<int, ViviendaFoto>
     */
    public function getViviendaFotos(): Collection
    {
        return $this->ViviendaFotos;
    }

    public function addViviendaFoto(ViviendaFoto $ViviendaFoto): static
    {
        if (!$this->ViviendaFotos->contains($ViviendaFoto)) {
            $this->ViviendaFotos->add($ViviendaFoto);
            $ViviendaFoto->setVivienda($this);
        }

        return $this;
    }

    public function removeViviendaFoto(ViviendaFoto $ViviendaFoto): static
    {
        if ($this->ViviendaFotos->removeElement($ViviendaFoto)) {
            // set the owning side to null (unless already changed)
            if ($ViviendaFoto->getVivienda() === $this) {
                $ViviendaFoto->setVivienda(null);
            }
        }

        return $this;
    }

  
 

   
   
}
