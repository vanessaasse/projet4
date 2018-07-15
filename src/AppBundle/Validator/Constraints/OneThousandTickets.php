<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class OneThousandTickets
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class OneThousandTickets extends Constraint
{
    public $nbTicketsByDay;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('nbTicketsByDay');
    }


    public function getMessage()
    {
        return 'Il n\'y a plus de billets disponibles à la réservation sur cette date. Veuillez effectuer une réservation sur une
        autre date.';
    }


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}