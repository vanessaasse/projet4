<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\Visit;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;

/**
 * Class TicketRepository
 * @package AppBundle\Repository
 */
class TicketRepository extends \Doctrine\ORM\EntityRepository
{
    /*private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->repository = $entityManager->getRepository(Ticket::class);
    }*/

    /**
     * @param \DateTime $visitDate
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countNbTicketForDate(\Datetime $visitDate)
    {
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.visit', 'visit')
            ->addSelect('visit');

        $qb->select('COUNT(t)')
            ->where('visit.visitDate = :visitDate')
            ->setParameter('visitDate', $visitDate);


        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
}
