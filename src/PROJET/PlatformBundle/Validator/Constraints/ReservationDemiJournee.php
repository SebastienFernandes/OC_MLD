<?php

namespace PROJET\PlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ReservationDemiJournee extends Constraint
{
    public $message = 'Seul les billets Demi-Journée sont acceptés une fois 14h00 passées.';

    public function getTargets()
    {
    	return self::CLASS_CONSTRAINT;
    }
}