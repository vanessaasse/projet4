<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Manager\VisitManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use AppBundle\Service\PublicHolidaysService;

class NoReservationOnPublicHolidaysValidator extends ConstraintValidator
{
    private $publicHolidaysService;

    public function __construct(PublicHolidaysService $publicHolidaysService)
    {
        $this->publicHolidaysService = $publicHolidaysService;

    }

    public function check(\DateTime $day)
    {
        $publicHolidays = $this->publicHolidaysService->getPublicHolidays($year = null);

        if (in_array($day->format('dmY'), $publicHolidays)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->check($value) == true) {

            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}