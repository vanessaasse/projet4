<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Assert\NotNull()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank(message="constraint.ticket.notblank.lastname", groups={"identification_registration"})
     * @Assert\Type(type="alpha", message="constraint.ticket.type.lastname", groups={"identification_registration"}) // TODO modifier le type pour permettre les accents et les traits d'union
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank(message="constraint.ticket.notblank.firstname", groups={"identification_registration"})
     * @Assert\Type(type="alpha", message="constraint.ticket.type.firstname", groups={"identification_registration"})
     *
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(groups={"identification_registration"})
     *
     */
    private $country;

    /**
     * @var \DateTime
     * @ORM\Column(name="birthday", type="date")
     * @Assert\Date(groups={"identification_registration"})
     *
     */
    private $birthday;

    /**
     * @var bool
     * @ORM\Column(name="discount", type="boolean")
     */
    private $discount;

    /**
     * @var int
     * @ORM\Column(name="price", type="integer")
     * @Assert\NotBlank(groups={"identification_registration"})
     */
    private $price;


    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Visit", inversedBy="tickets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid(groups={"identification_registration"})
     */
    private $visit;


    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        $this->birthday = (new \Datetime('2000-01-01'));
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
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return Ticket
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return Ticket
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set birthday.
     *
     * @param \DateTime $birthday
     *
     * @return Ticket
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday.
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set discount.
     *
     * @param bool $discount
     *
     * @return Ticket
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount.
     *
     * @return bool
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set price.
     *
     * @param int $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set visit.
     *
     * @param \AppBundle\Entity\Visit $visit
     *
     * @return Ticket
     */
    public function setVisit(\AppBundle\Entity\Visit $visit)
    {
        $this->visit = $visit;

        return $this;
    }

    /**
     * Get visit.
     *
     * @return \AppBundle\Entity\Visit
     */
    public function getVisit()
    {
        return $this->visit;
    }
}
