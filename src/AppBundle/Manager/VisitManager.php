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

    //private $visitDate;





    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

    }


    /**
     * Initialisation de la visite et de la session
     * Création de l'objet Visit
     *
     * @return Visit
     */
    public function initVisit()
    {
        $visit = new Visit();
        $this->whichVisitDay($visit);
        $this->session->set(self::SESSION_ID_CURRENT_VISIT,$visit);



        return $visit;
    }


    /**
     * Retourne la visite en cours dans la session
     *
     * @return Visit
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
     * Retourne le nombre de tickets en fonction du $nbticket demandé en page 2 "order"
     *
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
     * Affichage du datepicker sur la page Order
     *
     * @param Visit $visit
     */
    public function whichVisitDay(Visit $visit)
    {
        // TODO Ajouter les jours fériés
        date_default_timezone_set('Europe/Paris');
        $hour = date("H:i");
        $today = date("w");
        $tomorrow = date('w', strtotime('+1 day'));

        if($hour > "16:00" || $today == 0 || $today == 2) {

            $visitDate = (new \DateTime())->modify('+ 1 days');

            if($tomorrow  == 0 || $tomorrow == 2)
            {
                $visitDate = (new \DateTime())->modify('+ 2 days');
            }
        }
        else {

            $visitDate = (new \DateTime());
        }

        $visit->setVisitDate($visitDate);
        return $visitDate;
    }



    /**
     * @param Visit $visit
     * @param Ticket $ticket
     * @return int
     */
    public function computeTicketPrice(Ticket $ticket, Visit $visit)
    {
        $birthday = $ticket->getBirthday();
        $today = new \DateTime();
        $age = date_diff($birthday, $today)->y;

        $discount = $ticket->getDiscount();

        if ($visit->getType() == 'Billet journée')
        {
            if ($age >= 12 && $age < 60) {
                $price = 16;

                if ($age >= 12 && $age < 60 && $discount == true){
                    $price = 10;
                }

            } elseif ($age >= 60) {
                $price = 12;
            } elseif ($age >= 4 && $age < 12) {
                $price = 8;
            } else {
                $price = 0;
            }
        }

        elseif ($visit->getType() == 'Billet demi-journée (à partir de 14h)')
        {
            if ($age >= 12 && $age < 60) {
                $price = 8;

                if ($age >= 12 && $age < 60 && $discount == true){
                    $price = 5;
                }

            } elseif ($age >= 60) {
                $price = 6;
            } elseif ($age >= 4 && $age < 12) {
                $price = 4;
            } else {
                $price = 0;
            }
        }

        $ticket->setPrice($price);
        return $price;
    }


    /**
     * @param Visit $visit
     */
    public function computePrice(Visit $visit)
    {
        $totalAmount = 0;

        foreach ($visit->getTickets() as $ticket) {

            $priceTicket = $this->computeTicketPrice($ticket, $visit);

            $totalAmount += $priceTicket;

        }

        $visit->setTotalAmount($totalAmount);
        return $totalAmount;

    }


    /**
     * @param Visit $visit
     */
    public function getBookingCode(Visit $visit)
    {
        $bookingCode = uniqid();

        $visit->setBookingCode($bookingCode);
        return $bookingCode;

    }




    //TODO méthode pour empêcher la résa de billets au dela de 1000 billets réservés

}