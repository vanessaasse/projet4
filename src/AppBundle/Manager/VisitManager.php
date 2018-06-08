<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Ticket;
use AppBundle\Entity\Visit;
use AppBundle\Exception\InvalidVisitSessionException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VisitManager
{
    const SESSION_ID_CURRENT_VISIT = "visit";

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    /**
     * @return Visit
     */
    public function initVisit()
    {
        $visit = new Visit();
        $this->session->set(self::SESSION_ID_CURRENT_VISIT,$visit);
        return $visit;
    }

    /**
     * @param Visit $visit
     */
    public function generateTickets(Visit $visit)
    {
        for($i=0; $i<=$visit->getNbTicket(); $i++)
        {
            $visit->addTicket(new Ticket());
        }
    }

    /**
     * @return mixed
     * @throws InvalidVisitSessionException
     */
    public function getCurrentVisit()
    {
        $visit = $this->session->get(self::SESSION_ID_CURRENT_VISIT);
        if(!$visit)
        {
            // TODO Créer la méthode associée
            throw new InvalidVisitSessionException();
        }
        return $visit;
    }
}