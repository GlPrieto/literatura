<?php
	namespace App\Controller;
	use App\Entity\Articulo;
	use App\Entity\Idioma;
	use App\Entity\Categoria;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	
	class controller extends AbstractController
	{
		//public function index()
		//{
        	//return $this->render('base.html.twig');
		//}
		public function index()
    	{
			//Tomaré en un inicio la plantilla listaArticulos.html.twig como página de inicio.
        	$entityManager = $this->getDoctrine()->getManager();
        	$articulos= $entityManager->getRepository(Articulo::class)->findAll();
        	return $this->render('listaArticulos.html.twig', array(
            	'articulos' => $articulos,
    		));    
		}
			
		public function verArticulo($id)
    	{
			$titulo = null;
        	$entityManager = $this->getDoctrine()->getManager();
        	$articulo= $entityManager->getRepository(Articulo::class)->find($id);
        	// Si no existe lanzamos una excepción.
    		if (!$articulo){
				throw $this->createNotFoundException(
				'No existe ningún artículo con id '.$id
				);
			}
			return $this->render('verArticulo.html.twig', array(
				'titulo' => $titulo,
				'articulo' => $articulo,
			));
		}
		
		public function nuevoArticulo(Request $request) 
		{
			$articulo = new Articulo();
			$form = $this->createFormBuilder($articulo)            
				->add('titulo', TextType::class)            
				->add('sipnosis', TextareaType::class)  
				->add('redaccion', TextareaType::class)
				->add('fechaPublicacion', DateType::class)
				->add('idioma', EntityType::class, [
					'class' => Idioma::class,
					'choice_label' => 'denominacion',
				])    
				->add('categoria', EntityType::class, [
					'class' => Categoria::class, 
					'choice_label' => 'denominacion',
				])           
				->add('save', SubmitType::class,
				array('label' => 'Añadir artículo'))            
				->getForm();
					
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				// De esta manera podemos rellenar la variable
				$nuevoArticulo = $form->getData();
				// Obtenemos el gestor de entidades de Doctrine
				$entityManager = $this->getDoctrine()->getManager();
				// Le decimos a doctrine que nos gustaría almacenar
				// el objeto de la variable en la base de datos
				$entityManager->persist($nuevoArticulo);
				// Ejecuta las consultas necesarias (INSERT en este caso)
				$entityManager->flush();
				//Redirigimos a una página de confirmación.
				return $this->redirectToRoute('app_articulo_creado');
				}
			return $this->render('nuevoArticulo.html.twig', array(
			'form' => $form->createView(),        
			));
		}
		

		public function editarArticulo(Request $request, $id)
    	{
        	$entityManager = $this->getDoctrine()->getManager();
        	$articulo= $entityManager->getRepository(Articulo::class)->find($id);
            if (!$articulo)
			{
            	throw $this->createNotFoundException(
            	'No existe ninguna noticia con id '.$id
            	);
    		}
    		$form = $this->createFormBuilder($articulo)            
				->add('titulo', TextType::class)     
				->add('sipnosis', TextareaType::class)
				->add('fechaPublicacion', DateType::class)  
				->add('redaccion', TextareaType::class)
				->add('idioma', EntityType::class, [
					'class' => Idioma::class,
					'choice_label' => 'denominacion',
				])    
				->add('categoria', EntityType::class, [
					'class' => Categoria::class, 
					'choice_label' => 'denominacion',
				])
				->add('save', SubmitType::class,
				array('label' => 'Añadir artículo'))            
				->getForm();
    		$form->handleRequest($request);

        	if ($form->isSubmitted() && $form->isValid())
			{
        		$articulo = $form->getData();
        		$entityManager->flush();
    			return $this->redirectToRoute('app_articulo_ver', array('id'=>$id));
    		}
    		return $this->render('nuevoArticulo.html.twig', array(
        	'form' => $form->createView(),
    		));
    	}
		

		public function articuloCreado()
    	{
			return $this->render('articuloCreado.html.twig');    
		}

		public function eliminarArticulo($id)
    	{
			$entityManager = $this ->getDoctrine()->getManager();
			$articulo = $entityManager -> getRepository(Articulo::class) -> find($id);
			if(!$articulo)
			{
				throw $this->createNotFoundException('No existe el articulo con id '.$id);
			}
			$entityManager -> remove($articulo);
			$entityManager -> flush();
			return $this->render('articuloBorrado.html.twig');    
		}
		
	}
		
