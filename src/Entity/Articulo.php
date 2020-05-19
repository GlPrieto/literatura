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
     * @ORM\ManyToOne(targetEntity="App\Entity\Idioma", inversedBy="articulos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idioma;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categoria", inversedBy="articulos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * 
     */
    private $imagen;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $imagenBase64;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="articulos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $autor;

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

    public function getIdioma(): ?Idioma
    {
        return $this->idioma;
    }

    public function setIdioma(?Idioma $idioma): self
    {
        $this->idioma = $idioma;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }
    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getImagenBase64()
    {
        return $this->imagenBase64;
    }

    public function setImagenBase64($imagenBase64)
    {
        $this->imagenBase64 = $imagenBase64;

        return $this;
    }

    public function getAutor(): ?Usuario
    {
        return $this->autor;
    }

    public function setAutor(?Usuario $autor): self
    {
        $this->autor = $autor;

        return $this;
    }
}
