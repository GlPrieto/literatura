<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdiomaRepository")
 */
class Idioma
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $denominacion;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Articulo", mappedBy="idioma")
     */
    private $articulos;

    public function __construct()
    {
        $this->articulos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenominacion(): ?string
    {
        return $this->denominacion;
    }

    public function setDenominacion(string $denominacion): self
    {
        $this->denominacion = $denominacion;

        return $this;
    }

    /**
     * @return Collection|Articulo[]
     */
    public function getArticulos(): Collection
    {
        return $this->articulos;
    }

    public function addArticulo(Articulo $articulo): self
    {
        if (!$this->articulos->contains($articulo)) {
            $this->articulos[] = $articulo;
            $articulo->addIdioma($this);
        }

        return $this;
    }

    public function removeArticulo(Articulo $articulo): self
    {
        if ($this->articulos->contains($articulo)) {
            $this->articulos->removeElement($articulo);
            $articulo->removeIdioma($this);
        }

        return $this;
    }
}
