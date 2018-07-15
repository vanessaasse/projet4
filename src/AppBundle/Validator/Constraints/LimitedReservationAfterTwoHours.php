<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class LimitedReservationAfterTwoHours
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class LimitedReservationAfterTwoHours extends Constraint
{
    public $hour;

    public $ticket;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('hour', 'ticket');
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