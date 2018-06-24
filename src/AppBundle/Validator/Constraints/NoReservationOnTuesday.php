<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * Class NoReservationOnTuesday
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class NoReservationOnTuesday extends Constraint
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
        return 'Il n\'est pas possible de réserver un billet en ligne pour la journée du mardi. Ce jour, le musée est fermé.';
    }
}