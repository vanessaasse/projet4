<?php

namespace AppBundle\Listener;


use AppBundle\Exception\InvalidVisitSessionException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;


class ExceptionListener
{

    private $router;
    private $visitManager;

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
     *
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        $exception = $event->getException();

        $response = new Response();


        if($exception instanceof HttpExceptionInterface) {
            $this->router->generate('homepage', array('_locale' => 'fr'));

            $event->setResponse(new Response($response));
        }



    }


}