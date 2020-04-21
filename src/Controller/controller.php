<?php
	//src/Controller/controller.php
	namespace App\Controller;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Reponse;
	
	class controller extends AbstractController
	{
		public function index()
		{
		//$entityManager = $this->getDoctrine()->getManager();
        	//$noticias= $entityManager->getRepository(Noticia::class)->findAll();
        	//return $this->render('index.html.twig', array(
        	//    'noticias' => $noticias,
    		//));
        	return $this->render('base.html.twig');
		}
	} 
