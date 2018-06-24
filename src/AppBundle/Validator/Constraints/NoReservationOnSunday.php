<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * Class NoReservationOnSunday
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class NoReservationOnSunday extends Constraint
{
    public $day;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('day');
    }


    public function getMessage()
    {
        return 'En ligne, il n\'est pas possible de réserver une visite pour la journée du dimanche.';
    }
}