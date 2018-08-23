<?php

namespace AppBundle\Listener;


use AppBundle\Exception\InvalidVisitSessionException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;


class ExceptionListener
{
    private $router;

    private $session;
    private $translator;

    /**
     * ExceptionListener constructor.
     * @param Router $router
     */
    public function __construct(Router $router, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if($exception instanceof InvalidVisitSessionException) {

            $url = $this->router->generate('homepage');
            $message = $this->translator->trans('exception.message_error');
            $this->session->getFlashBag()->add('error', $message);
            $event->setResponse(new RedirectResponse($url));
        }





    }


}