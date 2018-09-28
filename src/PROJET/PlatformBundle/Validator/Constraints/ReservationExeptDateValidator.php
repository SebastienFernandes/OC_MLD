<?php

namespace PROJET\PlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ReservationExeptDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value->format('d') === "01" & $value->format('m') === "05"){
            $this->addVio($constraint);
        }
        if ($value->format('d') === "01" & $value->format('m') === "11"){
            $this->addVio($constraint);
        }
        if ($value->format('d') === "25" & $value->format('m') === "12"){
            $this->addVio($constraint);
        }
    }

    public function addVio($constraint)
    {
    	$this->context->buildViolation($constraint->message)
               ->addViolation();
    }
}