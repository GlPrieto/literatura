<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Usuario;
use App\Form\RegistrationFormType;
use App\Entity\Articulo;
use App\Form\CrearArchivoFormType;

class UsuarioController {

    private $entityManager;
    private $RepoUsuario;
    private $RepoArticulo;

    public function __construct( EntityManagerInterface $entityManager ) 
    {
        $this->entityManager = $entityManager;
        $this->RepoArticulo = $entityManager->getRepository( 'App:Articulo' );
        $this->RepoUsuario = $entityManager->getRepository( 'App:Usuario' );
    }

    /**
    * @Route( "/crear-articulo", name = "usuario_crea_articulo" )
    *
    * @param Request $request
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */

    public function createEntryAction( Request $request ) {
        $articulo = new Articulo();

        $usuario = $this->RepoUsuario->findOneByUsername( $this->getUser()->getUserName() );
        $articulo->setAuthor( $usuario );

        $form = $this->createForm( CrearArticuloFormType::class, $articulo );
        $form->handleRequest( $request );

        // Check is valid
        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->entityManager->persist( $articulo );
            $this->entityManager->flush( $articulo );

            $this->addFlash( 'success', 'Congratulations! Your post is created' );

            return $this->redirectToRoute( 'admin_entries' );
        }

        return $this->render( 'articulo/nuevoArticulo.html.twig', [
            'form' => $form->createView()
        ] );
    }
}
