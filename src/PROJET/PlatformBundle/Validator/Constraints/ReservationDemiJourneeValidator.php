<?php

namespace PROJET\PlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ReservationDemiJourneeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $today = new \DateTime('14:00');

        if ($value->getDate()->format('Y-m-d') === $today->format('Y-m-d') && $value->getDate()->format('h:i') > $today->format('H:i'))
        {
            $this->addVio($constraint);
        }
    }

    public function addVio($constraint)
    {
    	$this->context->buildViolation($constraint->message)
               ->addViolation();
    }
}