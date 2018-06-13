<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Ticket;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Customer;
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


    /**
     * @param Visit $visit
     */
    public function generateTickets(Visit $visit)
    {
        for($i= 1; $i<=$visit->getNbTicket(); $i++)
        {
            $visit->addTicket(new Ticket());
        }
    }


    /**
     * @param Visit $visit
     *
     */
    public function createTickets(Visit $visit)
    {
        $visit->getTickets();
    }


    /**
     * @param Visit $visit
     */
    public function createCustomer(Visit $visit)
    {
        $visit->getCustomer();
    }


    /**
     * @param Visit $visit
     *
     */
    public function createValidation(Visit $visit)
    {
        $visit->getVisitDate();
        $visit->getType();
        $visit->getCustomer();
        $visit->getNbTicket();
        $visit->getTickets();

    }


    /**
     * @param Visit $visit
     * @param Ticket $ticket
     * @return int
     */
    public function priceTicket(Visit $visit, Ticket $ticket)
    {
        $birthday = $ticket->getBirthday();
        $today = new \DateTime();
        $age = date_diff($birthday, $today);
        $age->format('%R%y years');

        if ($visit->getType() == 'Billet journée')
        {
            if ($age >= '12y' || $age < '60y') {
                $price = 16;
            } elseif ($age >= '60y') {
                $price = 12;
            } elseif ($age >= '4y' && $age < '12y') {
                $price = 8;
            } else {
                $price = 0;
            }

            return $price;
        }

        else {

            // si le billet est sur une demi-journée

            if ($age >= '12y' || $age < '60y') {
                $price = 8;
            } elseif ($age >= '60y') {
                $price = 6;
            } elseif ($age >= '4y' && $age < '12y') {
                $price = 4;
            } else {
                $price = 0;
            }

            return $price;
        }
    }


    public function totalPriceVisit(Visit $visit)
    {

    }



}