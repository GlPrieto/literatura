<?php
//src/Service/SubidaArchivos Es un servicio
// que gestiona la subida de cualquier archivo
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class SubidaArchivos
{
    private $directorioDestino;
    private $slugger;

    public function __construct($directorioDestino, SluggerInterface $slugger)
    {
        $this->directorioDestino = $directorioDestino;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $archivo)
    {
        $nombreOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $nombreGuardado = $this->slugger->slug($nombreOriginal);
        $nombreArchivo = $nombreGuardado.'-'.uniqid().'.'.$archivo->guessExtension();

        try {
            $archivo->move($this->getDirectorioDestino(), $nombreArchivo);
        } catch (FileException $e) {
            // ... handle exception if something happens during archivo upload
        }

        return $nombreArchivo;
    }

    public function getDirectorioDestino()
    {
        return $this->directorioDestino;
    }
}