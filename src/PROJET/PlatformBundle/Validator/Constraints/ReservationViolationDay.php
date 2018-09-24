<?php

namespace PROJET\PlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ReservationViolationDay extends Constraint
{
    public $message = 'Le musée ferme ses portes tous les mardis et dimanches';
}