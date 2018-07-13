<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as LouvreAssert;

/**
 * Visit
 *
 * @ORM\Table(name="visit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VisitRepository")
 *
 *
 */
class Visit
{

    const IS_VALID_INIT = ["order_registration"];
    //const IS_VALID_WITH_TICKET = array("order_registration", "or");

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="invoiceDate", type="datetime")
     */
    private $invoiceDate;

    /**
     *
     *
     * @ORM\Column(name="visitDate", type="date")
     * @LouvreAssert\ToLateForToday(hour=16, groups={"order_registration"})
     * @LouvreAssert\NoReservationOnTuesday(day=2, groups={"order_registration"})
     * @LouvreAssert\NoReservationOnSunday(day=0, groups={"order_registration"})
     * @LouvreAssert\NoReservationOnPublicHolidays(publicHolidays="",groups={"order_registration"})
     * @Assert\Range(min="today", minMessage="Vous devez choisir une date de visite supérieure ou égale à la date du jour.",
     *     max="+1 year", maxMessage="La réservation est uniquement sur l'année en cours", groups={"order_registration"})
     */
    private $visitDate;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\NotNull(message="Vous devez choisir un type de billet.", groups={"order_registration"})
     *
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(name="nbTicket", type="integer")
     * @Assert\Range(min=1, max=20)
     */
    private $nbTicket;

    /**
     * @var int
     * @ORM\Column(name="totalAmount", type="integer")
     */
    private $totalAmount;

    /**
     * @var string
     * @ORM\Column(name="bookingCode", type="string", unique=true)
     */
    private $bookingCode;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid(groups={"order_registration"})
     */
    private $customer;


    /**
     * @var Ticket[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="visit", cascade={"persist"})
     * @Assert\Valid(groups={"order_registration"})
     */
    private $tickets;


    /**
     * Visit constructor.
     */
    public function __construct()
    {
        $this->setInvoiceDate(new \DateTime());
        $this->tickets = new ArrayCollection();
        //$this->visitDate = (new \DateTime())/*->modify('+1 day')*/;

    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set invoiceDate.
     *
     * @param \DateTime $invoiceDate
     *
     * @return Visit
     */
    public function setInvoiceDate($invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    /**
     * Get invoiceDate.
     *
     * @return \DateTime
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * Set visitDate.
     *
     * @param \DateTime $visitDate
     *
     * @return Visit
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate.
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Visit
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set nbTicket.
     *
     * @param int $nbTicket
     *
     * @return Visit
     */
    public function setNbTicket($nbTicket)
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    /**
     * Get nbTicket.
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
    }

    /**
     * Set totalAmount.
     *
     * @param int $totalAmount
     *
     * @return Visit
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount.
     *
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set bookingCode.
     *
     * @param int $bookingCode
     *
     * @return Visit
     */
    public function setBookingCode($bookingCode)
    {
        $this->bookingCode = $bookingCode;

        return $this;
    }

    /**
     * Get bookingCode.
     *
     * @return int
     */
    public function getBookingCode()
    {
        return $this->bookingCode;
    }

    /**
     * Set customer.
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Visit
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get customer.
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add ticket.
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Visit
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setVisit($this);

        return $this;
    }

    /**
     * Remove ticket.
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        return $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets.
     *
     * @return Ticket[]|\Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
