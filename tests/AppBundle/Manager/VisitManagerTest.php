<?php

namespace tests\AppBundle\Manager;

use AppBundle\Entity\Visit;
use AppBundle\Entity\Ticket;
use AppBundle\Manager\VisitManager;
use AppBundle\Service\PublicHolidaysService;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

class VisitManagerTest extends TestCase
{
    public function testComputerTicketPrice()
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


        $birthday = date(1982-02-19);
        $today = new \DateTime();
        $age = date_diff($birthday, $today)->y;

        $visit = $ticket->getVisit(Visit::TYPE_FULL_DAY);
        $price = 16;


        $visitManager
            ->method('computeTicketPrice')
            ->with($ticket)
            ->willReturn($price);

        $this->assertSame(16, $visitManager->computeTicketPrice($ticket));
    }
}