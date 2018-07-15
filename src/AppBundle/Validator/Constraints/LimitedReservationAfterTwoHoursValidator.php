<?php

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class LimitedReservationAfterTwoHoursValidator
 * @package AppBundle\Validator\Constraints
 *
 */
class LimitedReservationAfterTwoHoursValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $hour = date("H");

        if($value->format('dmY') === date('dmY') && $hour >= $constraint->hour && $ticket = $constraint->ticket)
        {
            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }


}