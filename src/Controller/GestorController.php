<?php

namespace App\Controller;

use App\Entity\Articulo;
use App\Entity\Idioma;
use App\Entity\Categoria;
use App\Entity\Usuario;
use App\Form\NuevoArticuloFormType;
use App\Form\EditarArticuloFormType;
use App\Service\SubidaArchivos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;
//Generar PDF
use Dompdf\Dompdf;
use Dompdf\Options;

class GestorController extends AbstractController {
    /**
    * @Route( "/", name = "home" )
    */

    public function index() {
        //Tomaré en un inicio la plantilla listaArticulos.html.twig como página de inicio.
        $entityManager = $this->getDoctrine()->getManager();
        $articulos = $entityManager->getRepository( Articulo::class )->findAll();
        $categorias = $entityManager->getRepository( Categoria::class )->findAll();
        $idiomas = $entityManager->getRepository( Idioma::class )->findAll();
        $autores = $entityManager->getRepository( Usuario::class )->findAll();
        return $this->render( 'articulo/listaArticulos.html.twig', array(
            'categorias' => $categorias,
            'idiomas' => $idiomas,
            'articulos' => $articulos,
            'autores' => $autores,
        ) );

    }
    public function consultas() {
        $entityManager = $this->getDoctrine()->getManager();
        $articulos = $entityManager->getRepository( Articulo::class )->findAll();
        $articulosPorCatActual = $entityManager->getRepository( Articulo::class )->mostrarArticulosPorCategoriaMasReciente();
        $categorias = $entityManager->getRepository( Categoria::class )->findAll();
        return $this->render( 'consultas.html.twig', array(
            'categorias' => $categorias,
            'articulos' => $articulos,
            'articulosPorCatActual' => $articulosPorCatActual,
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

    public function articulosPorIdioma($id) {

        $entityManager = $this->getDoctrine()->getManager();
        $articulos = $entityManager->getRepository( Articulo::class )->mostrarArticulosPorIdioma ($id);
        //Debería buscar en la entidad idioma, por el id dado, la denominación
        $idioma = $entityManager->getRepository( Idioma::class )->find ($id);
        return $this->render( 'articulo/listaArticuloPorIdioma.html.twig', array(
            'idioma' => $idioma,
            'articulos' => $articulos,
        ) );
    }

    public function articulosPorAutor($id) {

        $entityManager = $this->getDoctrine()->getManager();
        $articulos = $entityManager->getRepository( Articulo::class )->mostrarArticulosPorAutor ($id);
        //Debería buscar en la entidad idioma, por el id dado, la denominación
        $autor = $entityManager->getRepository( Usuario::class )->find ($id);
        return $this->render( 'articulo/listaArticuloPorAutor.html.twig', array(
            'autor' => $autor,
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

    public function verArticuloPDF( $id ) {
        $titulo = null;
        $entityManager = $this->getDoctrine()->getManager();
        $articulo = $entityManager->getRepository( Articulo::class )->find( $id );
        // Si no existe lanzamos una excepción.
        if ( !$articulo ) {
            throw $this->createNotFoundException(
                'No existe ningún artículo con id '.$id
            );
        }
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('articulo/verArticuloPDF.html.twig', [
            'titulo' => $titulo,
            'articulo' => $articulo,
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }

    public function nuevoArticulo(Request $request,
                                     UserInterface $user, 
                                    SluggerInterface $slugger ,
                                    SubidaArchivos $subidaArchivo
    )
    {
        $articulo = new Articulo();
        $articulo->setAutor( $user );
        $articulo->setFechaPublicacion(new \DateTime('now'));
        $form = $this->createForm( NuevoArticuloFormType::class, $articulo);
        $form->handleRequest($request);
        
        if ( $form->isSubmitted() && $form->isValid() ) {
            /** @var UploadedFile $imagen */
            $imagen = $form['image']->getData();
            
            if ($imagen) {
                //aplicarle base64 encode, decode -> guardarlo en una base de datos
                $imagenBase64 = base64_encode(file_get_contents($imagen));
                $articulo->setImagenBase64( $imagenBase64 );
                $nombreImagen = $subidaArchivo->upload($imagen);
                $articulo->setImagen($nombreImagen);
            }    
            
            // Obtenemos el gestor de entidades de Doctrine
            $entityManager = $this->getDoctrine()->getManager();
            // Le decimos a doctrine que nos gustaría almacenar
            // el objeto de la variable en la base de datos
            $entityManager->persist($articulo);
            // Ejecuta las consultas necesarias (INSERT en este caso)
            $entityManager->flush($articulo);
            //Redirigimos a una página de confirmación.
            return $this->redirectToRoute('index');
        }
        return $this->render('articulo/nuevoArticulo.html.twig', array(
        'nuevoArticuloForm' => $form->createView(),        
        ));
    }

    public function editarArticulo( Request $request, $id, SluggerInterface $slugger,
    SubidaArchivos $subidaArchivo) {
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
            /** @var UploadedFile $imagen */
            $imagen= $form['image']->getData();

            if ($imagen) {
            //Copiado de nuevo articulo
            //aplicarle base64 encode, decode -> guardarlo en una base de datos
            $imagenBase64 = base64_encode(file_get_contents($imagen));
            $articulo->setImagenBase64( $imagenBase64 );
                $nombreImagen = $subidaArchivo->upload($imagen);
                $articulo->setImagen($nombreImagen);
            }    
            //
             // Obtenemos el gestor de entidades de Doctrine
             $entityManager = $this->getDoctrine()->getManager();
             // Le decimos a doctrine que nos gustaría almacenar
             // el objeto de la variable en la base de datos
             $entityManager->persist($articulo);
             // Ejecuta las consultas necesarias (INSERT en este caso)
             $entityManager->flush($articulo);
             //Redirigimos a una página de confirmación.
            return $this->redirectToRoute( 'app_articulo_ver', array( 'id'=>$id ) );
        }
        return $this->render( 'articulo/editarArticulo.html.twig', array(
            'editarArticuloForm' => $form->createView(),
        ) );
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

