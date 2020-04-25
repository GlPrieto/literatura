<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticuloRepository")
 */
class Articulo
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
    private $titulo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sipnosis;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaPublicacion;

    /**
     * @ORM\Column(type="text")
     */
    private $redaccion;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Idioma", inversedBy="articulos")
     */
    private $idioma;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categoria", inversedBy="articulos")
     */
    private $categorias;

    public function __construct()
    {
        $this->idioma = new ArrayCollection();
        $this->categorias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getSipnosis(): ?string
    {
        return $this->sipnosis;
    }

    public function setSipnosis(?string $sipnosis): self
    {
        $this->sipnosis = $sipnosis;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getRedaccion(): ?string
    {
        return $this->redaccion;
    }

    public function setRedaccion(string $redaccion): self
    {
        $this->redaccion = $redaccion;

        return $this;
    }

    /**
     * @return Collection|Idioma[]
     */
    public function getIdioma(): Collection
    {
        return $this->idioma;
    }

    public function addIdioma(Idioma $idioma): self
    {
        if (!$this->idioma->contains($idioma)) {
            $this->idioma[] = $idioma;
        }

        return $this;
    }

    public function removeIdioma(Idioma $idioma): self
    {
        if ($this->idioma->contains($idioma)) {
            $this->idioma->removeElement($idioma);
        }

        return $this;
    }

    /**
     * @return Collection|Categoria[]
     */
    public function getCategorias(): Collection
    {
        return $this->categorias;
    }

    public function addCategoria(Categoria $categoria): self
    {
        if (!$this->categorias->contains($categoria)) {
            $this->categorias[] = $categoria;
        }

        return $this;
    }

    public function removeCategoria(Categoria $categoria): self
    {
        if ($this->categorias->contains($categoria)) {
            $this->categorias->removeElement($categoria);
        }

        return $this;
    }

}
