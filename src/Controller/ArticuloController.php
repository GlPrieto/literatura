<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\Routing\Annotation\Route;
/** To call to Repository and entityManager */
use Doctrine\ORM\EntityManagerInterface;
/** To gather data */
use Symfony\Component\HttpFoundation\Request;
/** Maps objects to the database */
use App\Entity\Usuario;
/** will specify the input values users can provide when creating a new User */
use App\Form\RegistrationFormType;

class ArticuloController {

    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $RepoUsuario;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $RepoArticulo;

    /**
    * @param EntityManagerInterface $entityManager
    */

    public function __construct( EntityManagerInterface $entityManager ) {
        $this->entityManager = $entityManager;
        $this->RepoArticulo = $entityManager->getRepository( 'App:Articulo' );
        $this->RepoUsuario = $entityManager->getRepository( 'App:Usuario' );
    }
    /**
    * @Route( "/", name = "home" )
    * @Route( "/articulos", name = "articulos" )
    */

    public function listarArticulos() {
        return $this->render( 'articulo/listaArticulos.html.twig', [
            'articulos' => $this->RepoArticulo->findAll()
        ] );
    }
}
