<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CheckSiEsAutorListener
{
    protected $router;
    protected $session;
    private $tokenStorage;
    private $entityManager;

    public function __construct(
        RouterInterface $router,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager
    ) {
        $this->router = $router;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * On kernel.controller
     *
     * @param FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(ControllerEvent $event)
    {
        if (!preg_match('/^\/admin/i', $event->getRequest()->getPathInfo())) {
            return;
        }

        if (null === $user = $this->tokenStorage->getToken()->getUser()) {
            return;
        }

        if (true === $this->session->get('usuario_es_autor')) {
            return;
        }

        $route = $this->router->generate('usuario_crear');

        if (0 === strpos($event->getRequest()->getPathInfo(), $route)) {
            return;
        }

        if ($author = $this->entityManager
            ->getRepository('App:Usuario')
            ->findOneByUsername($user->getUsername())
        ) {
            $this->session->set('usuario_es_autor', true);
        }

        if (!$author && $this->session->get('pending_usuario_es_autor')) {
            $this->session->getFlashBag()->add(
                'warning',
                'Your author access is being set up, this may take up to 30 seconds. Please try again shortly.'
            );

            $route = $this->router->generate('home');
        } else {
            $this->session->getFlashBag()->add(
                'warning',
                'No puedes acceder a esta sesión si no eres usuario. Para acceder a esta opción, registrese'
            );
        }

        $event->setController(function () use ($route) {
            return new RedirectResponse($route);
        });
    }
}