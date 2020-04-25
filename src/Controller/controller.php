<?php
	//src/Controller/controller.php
	namespace App\Controller;
	use App\Entity\Articulo;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Reponse;
	
	class controller extends AbstractController
	{
		//public function index()
		//{
        	//return $this->render('base.html.twig');
		//}
		public function index()
    		{
        		$entityManager = $this->getDoctrine()->getManager();
        		$articulos= $entityManager->getRepository(Articulo::class)->findAll();
        		return $this->render('listaArticulos.html.twig', array(
            		'articulos' => $articulos,
    			));    
    		}
	} 
