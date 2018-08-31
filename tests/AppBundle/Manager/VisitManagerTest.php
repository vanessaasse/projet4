<?php

namespace tests\AppBundle\Manager;

use AppBundle\Entity\Visit;
use AppBundle\Entity\Ticket;
use AppBundle\Manager\VisitManager;
use PHPUnit\Framework\TestCase;

class VisitManagerTest extends TestCase
{


    /*
     * Test
     * Compute Price on one visit with 5 tickets
     */
    public function testComputePrice()
    {
        $visitManager = $this->VisitManagerAndDependenciesMocks();
        $visit = $this->createOneVisitOnFullDay();
        $price = $visitManager->computePrice($visit);

        $this->assertSame(58, $price);
    }


    /**
     *
     * Test
     * Compute price of one senior on full day
     * Check if discount is not apply
     */
    public function testComputeTicketPrice()
    {
        $visitManager = $this->VisitManagerAndDependenciesMocks();
        $visit = $this->createOneVisitForSenior();
        $price = $visitManager->computeTicketPrice($visit->getTickets()->first());

        $this->assertSame(12, $price);
    }



    /**
     * @return VisitManager
     *
     * Create VisitManager and dependancies for tests on VisitManager
     */
    public function VisitManagerAndDependenciesMocks()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\SessionInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $publicHolidaysService = $this->getMockBuilder('AppBundle\Service\PublicHolidaysService')
            ->disableOriginalConstructor()
            ->getMock();

        $validator = $this->getMockBuilder('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $emailService = $this->getMockBuilder('AppBundle\Service\EmailService')
            ->disableOriginalConstructor()
            ->getMock();

        $visitManager = new VisitManager($session, $publicHolidaysService, $validator, $emailService);

        return $visitManager;
    }


    /**
     * @return Visit
     * Create one visit for senior
     * Check that discount is not apply to senior people
     *
     */
    public function createOneVisitForSenior()
    {
        $visit = new Visit;
        $date = new \DateTime("27-05-2019");
        $visit->setVisitDate($date);
        $type = Visit::TYPE_FULL_DAY;
        $visit->setType($type);

        $visit->addTicket($this->createTicket("04-04-1957", 1));

        return $visit;
    }


    /**
     * @return Visit
     * Create one visit on full day for tests
     */
    public function createOneVisitOnFullDay()
    {
        $visit = new Visit;
        $date = new \DateTime("27-05-2019");
        $visit->setVisitDate($date);
        $type = Visit::TYPE_FULL_DAY;
        $visit->setType($type);

        $tickets = array(
            $ticket1 = $this->createTicket("19-02-1982", 0), // 16
            $ticket2 = $this->createTicket("19-02-1982", 1), // 10
            $ticket3 = $this->createTicket("04-04-1957", 0), // 12
            $ticket4 = $this->createTicket("04-04-1957", 1), // 12 no discount for senior
            $ticket5 = $this->createTicket("02-06-2008", 0), // 8
            $ticket5 = $this->createTicket("30-09-2015", 0) // 0
        );

        foreach ($tickets as $ticket)
        {
            $visit->addTicket($ticket);
        }

        return $visit;
    }


    /**
     * @param $date
     * @param $discount
     * @return Ticket
     *
     * Create Ticket for tests
     */
    public function createTicket($date, $discount)
    {
        $ticket = new Ticket();
        $birthday = new \DateTime($date);

        $ticket->setBirthday($birthday);
        $ticket->setDiscount($discount);

        return $ticket;

    }
}