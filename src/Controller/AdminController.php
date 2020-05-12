<?php

namespace App\Controller;

use App\Entity\Articulo;
use App\Entity\Idioma;
use App\Entity\Categoria;
use App\Entity\Usuario;
use App\Form\NuevaCategoriaFormType;
use App\Form\NuevoIdiomaFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $usuarios = $entityManager->getRepository( Usuario::class )->findBy(array(),array('id'=>'DESC'),1,0);
        $categorias = $entityManager->getRepository( Categoria::class )->findAll();
        $idiomas = $entityManager->getRepository( Idioma::class )->findAll();
        $articulos = $entityManager->getRepository( Articulo::class )->findBy(array(),array('id'=>'DESC'),1,0);

        return $this->render( 'admin/index.html.twig', array(
            'usuarios' => $usuarios,
            'categorias' => $categorias,
            'idiomas' => $idiomas,
            'articulos' => $articulos,
        ) );

    }

    public function vistaArticulos()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorias = $entityManager->getRepository( Categoria::class )->findAll();
        $idiomas = $entityManager->getRepository( Idioma::class )->findAll();
        $articulos = $entityManager->getRepository( Articulo::class )->findAll();
        return $this->render( 'admin/vistaAdminArticulos.html.twig', array(
            'categorias' => $categorias,
            'idiomas' => $idiomas,
            'articulos' => $articulos,
        ) );

    }

    public function eliminarArticulo($id)
    {
        $entityManager = $this ->getDoctrine()->getManager();
        $articulo = $entityManager -> getRepository( Articulo::class ) -> find( $id );
        if ( !$articulo ) {
            throw $this->createNotFoundException( 'No existe el articulo con id '.$id );
        }
        //$this->denyAccessUnlessGranted('delete', $articulo);

        $entityManager -> remove( $articulo );
        $entityManager -> flush();
        $this->addFlash('success', 'post.deleted_successfully');
        return $this->render( 'admin/vistaAdminArticulos.html.twig' );

    }

    public function vistaUsuarios()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $usuarios = $entityManager->getRepository( Usuario::class) ->findAll();
        return $this->render( 'admin/vistaAdminUsuarios.html.twig', array(
            'usuarios' => $usuarios,
        ) );

    }

    public function eliminarUsuario($id)
    {
        $entityManager = $this ->getDoctrine()->getManager();
        $usuario = $entityManager -> getRepository( Usuario::class ) -> find( $id );
        if ( !$usuario ) {
            throw $this->createNotFoundException( 'No existe el usuario con id '.$id );
        }
        $entityManager -> remove( $usuario );
        $entityManager -> flush();
        return $this->render( 'admin/vistaAdminUsuarios.html.twig' );

    }
    public function nuevaCategoria(Request $request) 
    {
        $categoria = new Categoria();
        $form = $this->createForm( NuevaCategoriaFormType::class, $categoria);
        $form->handleRequest($request);

        //Redirigimos a una página de confirmación.
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtenemos el gestor de entidades de Doctrine
            $entityManager = $this->getDoctrine()->getManager();
            // Le decimos a doctrine que nos gustaría almacenar
            // el objeto de la variable en la base de datos
            $entityManager->persist($categoria);
            // Ejecuta las consultas necesarias (INSERT en este caso)
            $entityManager->flush($categoria);
            return $this->redirectToRoute( 'admin');
        }
        return $this->render('categoria/nuevaCategoria.html.twig', array(
        'nuevaCategoriaForm' => $form->createView(),        
        ));
    }
    public function nuevoIdioma(Request $request) 
    {
        $idioma = new Idioma();
        $form = $this->createForm( NuevoIdiomaFormType::class, $idioma);
        $form->handleRequest($request);
       //Redirigimos a una página de confirmación.
       if ($form->isSubmitted() && $form->isValid()) {
         // Obtenemos el gestor de entidades de Doctrine
         $entityManager = $this->getDoctrine()->getManager();
         // Le decimos a doctrine que nos gustaría almacenar
         // el objeto de la variable en la base de datos
         $entityManager->persist($idioma);
         // Ejecuta las consultas necesarias (INSERT en este caso)
         $entityManager->flush($idioma);
        return $this->redirectToRoute( 'admin');
    }
        //Redirigimos a una página de confirmación.
        return $this->render('idioma/nuevoIdioma.html.twig', array(
        'nuevoIdiomaForm' => $form->createView(),        
        ));
    }
}
