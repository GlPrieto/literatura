<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Articulo;
use App\Form\EditarUsuarioFormType;
use App\Form\NuevoArticuloFormType;
use App\Form\EditarArticuloFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
//use App\Service\SubidaArchivos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController {

    
    
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

    public function editarUsuario( Request $request, $id, SluggerInterface $slugger ) {
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
            /** @var ArchivoSubido $imageFile */
            $imagen= $form['image']->getData();

            // Concición necesaria. El archivo debe ser procesado solo cuando se carga
            if ( $imagen ) {
                $nombreOriginalImagen = pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME);
                 //-> upload($imagen);
                $nombreGuardado = $slugger->slug($nombreOriginalImagen);
                $nuevoNombreI = $nombreGuardado.'-'.uniqid().'.'.$imagen->guessExtension();
                try {
                    $imagen->move(
                        $this->getParameter('directorioImagenes'),//getTargetDirectory(), 
                        $nuevoNombreI
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during archivo upload
                }
                $usuario->setImagenPerfil( $nuevoNombreI );
            }
            // Obtenemos el gestor de entidades de Doctrine
            $entityManager = $this->getDoctrine()->getManager();

            // Ejecuta las consultas necesarias (UPDATE en este caso)
            $entityManager->flush($usuario);
            return $this->redirectToRoute( 'app_perfil_ver', array( 'id'=>$id ) );
        }
        return $this->render( 'usuario/editarPerfil.html.twig', array(
            'editarPerfilForm' => $form->createView(),
        ) );
    }
}
