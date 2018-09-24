<?php

namespace PROJET\PlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ReservationExeptDate extends Constraint
{
    public $message = 'Pas de réservation sur l\'application les dimanches et jours fériés';
}