<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ToLateForToday
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class ToLateForToday extends Constraint
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
        return 'Une fois '.$this->hour.'h passées, vous ne pouvez plus effectuer une réservation sur le jour en cours.';
    }


}