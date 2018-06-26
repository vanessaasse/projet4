<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NoReservationOnPublicHolidays
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class NoReservationOnPublicHolidays extends Constraint
{
    public $publicHolidays;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('publicHolidays');
    }


    public function getMessage()
    {
        return 'Réservation indisponible ce jour férié.';
    }


}