<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\RegistrationFormType;
use App\Security\FormularioLoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\SubidaArchivos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController {
    /**
    * @Route( "/register", name = "app_register" )
    */

    public function register( Request $request, 
                            UserPasswordEncoderInterface $passwordEncoder, 
                            GuardAuthenticatorHandler $guardHandler, 
                            FormularioLoginAuthenticator $authenticator, 
                            SluggerInterface $slugger, 
                            SubidaArchivos $subidaArchivo 
                            ): Response 
    {
        $user = new Usuario();
        $form = $this->createForm( RegistrationFormType::class, $user );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            // Concición necesaria para procesar solo cuando se sube
            /** @var UploadedFile $imagen */
            $imagen = $form['image']->getData();
            
            if ($imagen) {
                //aplicarle base64 encode, decode -> guardarlo en una base de datos
                $imagenBase64 = base64_encode(file_get_contents($imagen));
                $user->setImagenBase64( $imagenBase64 );
                $nombreImagen = $subidaArchivo->upload($imagen);
                $user->setImagenPerfil( $nuevoNombreI );
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
            // after validating the user and saving them to the database
            // authenticate the user and use onAuthenticationSuccess on the authenticator
            return $guardHandler->authenticateUserAndHandleSuccess(
            $user,          // the User object you just created
            $request,
            $authenticator, // authenticator whose onAuthenticationSuccess you want to use
            'main'          // the name of your firewall in security.yaml
        );
            return $this->redirectToRoute( 'home' );
        }

        return $this->render( 'registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ] );
    }
}
