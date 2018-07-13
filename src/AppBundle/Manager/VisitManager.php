<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Ticket;
use AppBundle\Entity\Visit;
use AppBundle\Entity\Customer;
use AppBundle\Exception\InvalidVisitSessionException;
use AppBundle\Service\PublicHolidaysService;
use Doctrine\ORM\Mapping as Embedded;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class VisitManager
 * @package AppBundle\Manager
 * @Embedded\Embedded
 */
class VisitManager
{
    const SESSION_ID_CURRENT_VISIT = "visit";

    /**
     * @var SessionInterface
     */
    private $session;

    private $publicHolidaysService;

    private $validator;


    public function __construct(SessionInterface $session, PublicHolidaysService $publicHolidaysService, ValidatorInterface $validator)
    {
        $this->session = $session;
        $this->publicHolidaysService = $publicHolidaysService;
        $this->validator = $validator;
    }


    /**
     * Page 2 - Order
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
     * Page 3 - Identification des visiteurs
     * Retourne la visite en cours dans la session
     *
     * @param array $group
     * @return Visit
     * @throws InvalidVisitSessionException
     */
    public function getCurrentVisit($validateBy = null)
    {
        $visit = $this->session->get(self::SESSION_ID_CURRENT_VISIT);

        if(!$visit instanceof Visit)
        {
            throw new InvalidVisitSessionException("Cette page est inaccessible.");
        }

        if(!empty($validateBy) && count($this->validator->validate($visit,null,$validateBy)) > 0)
        {
            throw new InvalidVisitSessionException("Commande invalide.");
        }

        return $visit;
    }


    /**
     * Page 2 - Order
     * Retourne le nombre de tickets en fonction du $nbticket demandé
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
     * Page 2 - Order
     * Affichage dans le datepicker sur la page Order
     *
     * @param Visit $visit
     * @param PublicHolidaysService $publicHolidaysService
     */
    public function whichVisitDay(Visit $visit)
    {
        date_default_timezone_set('Europe/Paris');
        $hour = date("H:i");
        $today = date("w");
        $tomorrow = date('w', strtotime('+1 day'));
        $publicHolidays = $this->publicHolidaysService->getPublicHolidaysOnTheseTwoYears();


        if($hour > "16:00" || $today == 0 || $today == 2 || $today == $publicHolidays) {
            $visitDate = (new \DateTime())->modify('+ 1 days');

            if($tomorrow  == 0 || $tomorrow == 2 || $tomorrow == $publicHolidays) {
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
     * Page 3 - Identification des visiteurs
     * Calcule le prix de chaque billet en fonction de l'âge et du type de billet
     *
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
     * Page 3 - Identification
     * Calcule le prix total de la visite
     *
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
     * Page 5 - Pay
     * Génère le bookingCode
     *
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