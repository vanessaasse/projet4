<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class LimitedReservationAfterTwoHours
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class LimitedReservationAfterHour extends Constraint
{
    public $hour;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('hour');
    }


    public function getMessage()
    {
        return 'Une fois '.$this->hour.'h passées, vous ne pouvez plus acheter de billet journée pour aujourd\'hui.';
    }


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}