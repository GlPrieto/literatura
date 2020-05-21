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
use App\Service\SubidaArchivos;
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
