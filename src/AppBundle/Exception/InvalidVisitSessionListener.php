<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


class InvalidVisitSessionListener
{

    protected $invalidVisitSessionException;

    public function __construct(InvalidVisitSessionException $invalidVisitSessionException)
    {
        $this->invalidVisitSessionException = $invalidVisitSessionException;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * @return mixed
     */
    public function ListeningException(GetResponseForExceptionEvent $event)
    {
        //TODO terminer les listeners
        $exception = $event->getException();

        if($exception instanceof HttpExceptionInterface) {

            $this->addFlash('notice', 'message.contact.send');
            return $this->redirect($this->generateUrl('homepage'));

        }

    }
}