<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Manager\VisitManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoReservationOnPublicHolidaysValidator extends ConstraintValidator
{
    public function publicHolidays($year = null)
    {
        if ($year === null)
        {
            $year = intval(date('Y'));
        }

        $easterDate  = easter_date($year);
        $easterDay   = date('d', $easterDate);
        $easterMonth = date('m', $easterDate);
        $easterYear   = date('Y', $easterDate);

        $publicHolidays = array(
            // Dates fixes
            date('dmY', mktime(0, 0, 0, 1,  1,  $year)), // nouvel an
            date('dmY', mktime(0, 0, 0, 5,  1,  $year)), // fête du travail
            date('dmY', mktime(0, 0, 0, 5,  8,  $year)), // 8 mai
            date('dmY', mktime(0, 0, 0, 7,  14, $year)), // 14 juillet
            date('dmY', mktime(0, 0, 0, 8,  15, $year)), // 15 août
            date('dmY', mktime(0, 0, 0, 11, 1,  $year)), // 1e nov
            date('dmY', mktime(0, 0, 0, 11, 11, $year)), // 11 nov
            date('dmY', mktime(0, 0, 0, 12, 25, $year)), // noel

            // Dates variables
            date('dmY',mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear)),
            date('dmY',mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear)),
            date('dmY',mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear)),
        );

        sort($publicHolidays);

        return $publicHolidays;
    }


    public function check($day)
    {
        $publicHolidays = $this->publicHolidays($year = null);

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

        dump($value);
    }
}