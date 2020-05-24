<?php

namespace App\Controller;

//Entidades
use App\Entity\Articulo;
use App\Entity\Usuario;
//Formularios a los que se llama
use App\Form\EditarArticuloFormType;
use App\Form\EditarUsuarioFormType;
use App\Form\NuevoArticuloFormType;
use App\Form\RegistrationFormType;
//Formulario login
use App\Security\FormularioLoginAuthenticator;
//Servicio de subida de archivos
use App\Service\SubidaArchivos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController {

    //Registro. En este caso usé ruta por anotaciones
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
    
    //Estos métodos usan herramientas del componente de seguridad de symfony
    //Login. Autenticación de usuario.
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    //Logout. Autenticación de usuario.
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    public function mostrarPerfil($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $usuario = $entityManager->getRepository( Usuario::class )->find($id);
        $articulos = $entityManager->getRepository( Articulo::class )->mostrarArticulosPorAutor($id);
        // Si no existe lanzamos una excepción.
        if ( !$usuario ) {
            throw $this->createNotFoundException(
                'No existe ningún usuario con id '.$id
            );
        }
        return $this->render( 'usuario/verPerfil.html.twig', array(
            'usuario' => $usuario,
            'articulos' => $articulos,
        ) );
    }

    public function editarUsuario( Request $request, $id, SluggerInterface $slugger, 
    SubidaArchivos $subidaArchivo ) {
        $entityManager = $this->getDoctrine()->getManager();
        // obtener un usuario
        $usuario = $entityManager->getRepository( Usuario::class )->find( $id );
        if ( !$usuario ) {
            throw $this->createNotFoundException(
                'No existe ningún usuario con id '.$id
            );
        }
        // check for "edit" access: calls all voters=permisos
        // The denyAccessUnlessGranted() method (and also the
        // isGranted() method) calls out to the "voter" system.
        $form = $this->createForm( EditarUsuarioFormType::class, $usuario);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imagen */
            $imagen = $form['image']->getData();
            
            if ($imagen) {
                //aplicarle base64 encode, decode -> guardarlo en una base de datos
                $imagenBase64 = base64_encode(file_get_contents($imagen));
                $usuario->setImagenBase64( $imagenBase64 );
                $nombreImagen = $subidaArchivo->upload($imagen);
                $usuario->setImagenPerfil($nombreImagen);
            }

            //
             // Obtenemos el gestor de entidades de Doctrine
             $entityManager = $this->getDoctrine()->getManager();
             // Le decimos a doctrine que nos gustaría almacenar
             // el objeto de la variable en la base de datos
             $entityManager->persist($usuario);
             // Ejecuta las consultas necesarias (INSERT en este caso)
             $entityManager->flush($usuario);
             //Redirigimos a una página de confirmación.
            $imagen= $form->get('image')->getData();
            
            return $this->redirectToRoute( 'app_perfil_ver', array( 'id'=>$id ) );
        }
        return $this->render( 'usuario/editarPerfil.html.twig', array(
            'editarPerfilForm' => $form->createView(),
        ) );
    }
}
