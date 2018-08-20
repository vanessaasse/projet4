<?php

namespace AppBundle\Exception;


use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
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
        $message = sprintf(
            'message.contact.send',
            $exception->getMessage(),
            $exception->getCode()
        );


        $response = new Response();
        $response->setContent($message);

        if($this == "Commande invalide") {
            return $this->redirect($this->generateUrl('homepage'));

        }

        if($this == "Cette page est inaccessible.")
        {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $event->setResponse($response);

    }
}