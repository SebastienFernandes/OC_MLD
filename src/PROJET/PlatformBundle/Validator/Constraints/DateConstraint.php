<?php

// src/AppBundle/Validator/Constraints/ContainsAlphanumeric.php

namespace PROJET\PlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateConstraint extends Constraint
{
    public $message = 'The string "{{ string }}" contains an illegal character: it can only contain letters or numbers.';
}