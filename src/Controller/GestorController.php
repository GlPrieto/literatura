<?php

namespace App\Controller;

use App\Entity\Articulo;
use App\Entity\Idioma;
use App\Entity\Categoria;
use App\Entity\Usuario;
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

class GestorController extends AbstractController {
    /**
    * @Route( "/", name = "home" )
    */

    public function index() {
        /*return $this->render( 'gestor/index.html.twig', [
            'controller_name' => 'GestorController',
        ] );
        */
        //Tomaré en un inicio la plantilla listaArticulos.html.twig como página de inicio.
        $entityManager = $this->getDoctrine()->getManager();
        $articulos = $entityManager->getRepository( Articulo::class )->findAll();
        $categorias = $entityManager->getRepository( Categoria::class )->findAll();
        return $this->render( 'articulo/listaArticulos.html.twig', array(
            'categorias' => $categorias,
            'articulos' => $articulos,
        ) );

    }

    public function articulosPorCategoria($idCat) {

        $entityManager = $this->getDoctrine()->getManager();
        $articulos = $entityManager->getRepository( Articulo::class )->mostrarArticulosPorCategoria ($idCat);
        //Debería buscar en la entidad categoria, por el id dado, la denominación
        $categoria = $entityManager->getRepository( Categoria::class )->find ($idCat);
        return $this->render( 'articulo/listaArticuloPorCategoria.html.twig', array(
            'categoria' => $categoria,
            'articulos' => $articulos,
        ) );

    }

    public function verArticulo( $id ) {
        $titulo = null;
        $entityManager = $this->getDoctrine()->getManager();
        $articulo = $entityManager->getRepository( Articulo::class )->find( $id );
        // Si no existe lanzamos una excepción.
        if ( !$articulo ) {
            throw $this->createNotFoundException(
                'No existe ningún artículo con id '.$id
            );
        }
        return $this->render( 'articulo/verArticulo.html.twig', array(
            'titulo' => $titulo,
            'articulo' => $articulo,
        ) );
    }

    public function nuevoArticulo(Request $request,
                                     UserInterface $user, 
                                    SluggerInterface $slugger 
    ) 
    {
        $articulo = new Articulo();
        $articulo->setAutor( $user );
        $articulo->setFechaPublicacion(new \DateTime('now'));
        $form = $this->createForm( NuevoArticuloFormType::class, $articulo);
        $form->handleRequest($request);
        
        if ( $form->isSubmitted() && $form->isValid() ) {
            /** @var UploadedFile $imagen */
            $imagen= $form->get('image')->getData();
            //$imagen= $form['image']->getData();

            // Concición necesaria para procesar solo cuando se sube
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
                $articulo->setImagen( $nuevoNombreI );
            }
            // Obtenemos el gestor de entidades de Doctrine
            $entityManager = $this->getDoctrine()->getManager();
            // Le decimos a doctrine que nos gustaría almacenar
            // el objeto de la variable en la base de datos
            $entityManager->persist($articulo);
            // Ejecuta las consultas necesarias (INSERT en este caso)
            $entityManager->flush($articulo);
            //Redirigimos a una página de confirmación.
            return $this->redirectToRoute('app_articulo_creado');
            }
        return $this->render('articulo/nuevoArticulo.html.twig', array(
        'nuevoArticuloForm' => $form->createView(),        
        ));
    }

    public function editarArticulo( Request $request, $id ) {
        $entityManager = $this->getDoctrine()->getManager();
        // obtener un articulo
        $articulo = $entityManager->getRepository( Articulo::class )->find( $id );
        if ( !$articulo ) {
            throw $this->createNotFoundException(
                'No existe ningún artículo con id '.$id
            );
        }
        // check for "edit" access: calls all voters=permisos
        // The denyAccessUnlessGranted() method (and also the
        // isGranted() method) calls out to the "voter" system.
        $this->denyAccessUnlessGranted('edit', $articulo);
        $form = $this->createForm( EditarArticuloFormType::class, $articulo);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ArchivoSubido $imageFile */
            $imagen= $form['image']->getData();

            // Concición necesaria. El archivo debe ser procesado solo cuando se carga
            if ( $imagen ) {
                $nombreOriginalImagen = $subidaArchivo -> upload($imagen);
                $articulo->setImagen( $nombreOriginalImagen );
            }
            // Obtenemos el gestor de entidades de Doctrine
            $entityManager = $this->getDoctrine()->getManager();

            // Ejecuta las consultas necesarias (UPDATE en este caso)
            $entityManager->flush($articulo);
            return $this->redirectToRoute( 'app_articulo_ver', array( 'id'=>$id ) );
        }
        return $this->render( 'articulo/editarArticulo.html.twig', array(
            'editarArticuloForm' => $form->createView(),
        ) );
    }

    public function articuloCreado() {
        return $this->render( 'articulo/articuloCreado.html.twig' );

    }

    public function eliminarArticulo( $id ) {
        $entityManager = $this ->getDoctrine()->getManager();
        $articulo = $entityManager -> getRepository( Articulo::class ) -> find( $id );
        if ( !$articulo ) {
            throw $this->createNotFoundException( 'No existe el articulo con id '.$id );
        }
        // check for "edit" access: calls all voters=permisos
        // The denyAccessUnlessGranted() method (and also the
        // isGranted() method) calls out to the "voter" system.
        $this->denyAccessUnlessGranted('delete', $articulo);

        $entityManager -> remove( $articulo );
        $entityManager -> flush();
        return $this->render( 'articulo/articuloBorrado.html.twig' );

    }

}

