<?php

namespace AppBundle\Exception;


use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


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
    public function ListeningException()
    {
        //TODO terminer les listeners

        if($this == "Commande invalide") {

            $this->addFlash('notice', 'message.contact.send');
            return $this->redirect($this->generateUrl('homepage'));

        }

        if($this == "Cette page est inaccessible.")
        {
            $this->addFlash('notice', 'message.contact.send');
            return $this->redirect($this->generateUrl('homepage'));
        }

    }
}