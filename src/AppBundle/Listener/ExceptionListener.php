<?php

namespace AppBundle\Listener;


use AppBundle\Exception\InvalidVisitSessionException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;



class ExceptionListener
{
    private $router;

    /**
     * ExceptionListener constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if($exception instanceof InvalidVisitSessionException) {
            $url = $this->router->generate('homepage');
            $event->setResponse(new RedirectResponse($url));
        }





    }


}