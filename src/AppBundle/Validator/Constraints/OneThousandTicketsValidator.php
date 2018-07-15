<?php


namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class OneThousandTicketsValidator
 * @package AppBundle\Validator\Constraints
 */
class OneThousandTicketsValidator extends ConstraintValidator
{
    private $em;

    /**
     * OneThousandTicketsValidator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $visitDate
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function checkNbTicketsOnThisDate($visitDate)
    {
        $nbTicketsByDay = $this->em->getRepository(Visit::class)->countNbTicketsOnThisDate($visitDate);
        dump($nbTicketsByDay);

        if($nbTicketsByDay >= 1000) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->checkNbTicketsOnThisDate($value) == true) {

            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }

}