<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Usuario;
use App\Form\RegistrationFormType;

class AdminController {

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
    * @Route( "/create-entry", name = "admin_create_entry" )
    *
    * @param Request $request
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */

    public function createEntryAction( Request $request ) {
        $blogPost = new BlogPost();

        $author = $this->authorRepository->findOneByUsername( $this->getUser()->getUserName() );
        $blogPost->setAuthor( $author );

        $form = $this->createForm( EntryFormType::class, $blogPost );
        $form->handleRequest( $request );

        // Check is valid
        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->entityManager->persist( $blogPost );
            $this->entityManager->flush( $blogPost );

            $this->addFlash( 'success', 'Congratulations! Your post is created' );

            return $this->redirectToRoute( 'admin_entries' );
        }

        return $this->render( 'admin/entry_form.html.twig', [
            'form' => $form->createView()
        ] );
    }
}
