<?php

namespace PROJET\PlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ReservationViolationDayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
    	if ($value->format('w') === "2"){
    		$this->addVio($constraint);
    	}
    	if ($value->format('w') === "0"){
    		$this->addVio($constraint);
    	}
    }

    public function addVio($constraint)
    {
    	$this->context->buildViolation($constraint->message)
               ->addViolation();
    }
}