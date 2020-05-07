<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\RegistrationFormType;
use App\Security\FormularioLoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use APP\Service\SubidaArchivos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController {
    /**
    * @Route( "/register", name = "app_register" )
    */

    public function register( Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, FormularioLoginAuthenticator $authenticator, SluggerInterface $slugger ): Response {
        $user = new Usuario();
        $form = $this->createForm( RegistrationFormType::class, $user );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            /** @var ArchivoSubido $imageFile */
            $imagen= $form['image']->getData();

            // Concición necesaria. El archivo debe ser procesado solo cuando se carga
            if ( $imagen ) {
                $nombreOriginalImagen = $subidaArchivo -> upload($imagen);
                $user->setImagenPerfil( $nombreOriginalImagen );
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get( 'plainPassword' )->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist( $user );
            $entityManager->flush( $user );
            $request->getSession()->set( 'usuario_es_autor', true );
            $this->addFlash( 'success', '¡Enhorabuena! Ahora serás reconocido como autor en esta web.' );

            return $this->redirectToRoute( 'home' );
        }

        return $this->render( 'registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ] );
    }
}
