<?php

namespace PROJET\PlatformBundle\Count;

use PROJET\PlatformBundle\Entity\TicketCount;
use PROJET\PlatformBundle\Validator\Constraints\ReservationExeptDate;
use PROJET\PlatformBundle\Validator\Constraints\ReservationViolationDay;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class Check
{
    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em        = $em;
        $this->validator = $validator;
    }

    public function checkTicketCountToDay() 
    {
        $day = new \DateTime();
        $day->setTime(00, 00, 00);
        $reservDate = $this->reservDate($this->em, $day);
        if ($reservDate === null){
            return $ticketCountToDay = 0;
        }
        return $ticketCountToDay = $reservDate->getNumbers();
    }

    public function checkTicketCount($request) 
    {
        $m          = $request->request->get('month');
        $d          = $request->request->get('day');
        $y          = $request->request->get('year');
        $day        = new \DateTime($y .'/'. $m .'/'. $d);

        $errorExept      = $this->validator->validate($day, new ReservationExeptDate());
        $errorViolation  = $this->validator->validate($day, new ReservationViolationDay());

        $toDay      = (new \DateTime())->modify('-1 day');
        $reservDate = $this->reservDate($this->em, $day);

        if ($day < $toDay){
            return $ticketCount = "<style> #places{color : red; font-weight: bold;}</style>  Attention date non valide! ";
        }else if (\count($errorExept) > 0){
            $message = $errorExept[0]->getMessage();
            return $ticketCount = "<style> #places{color : red; font-weight: bold;}</style>  $message ";
        }else if (\count($errorViolation) > 0){
            $message = $errorViolation[0]->getMessage();
            return $ticketCount = "<style> #places{color : red; font-weight: bold;}</style>  $message ";
        }else if ($reservDate === null){
            return $ticketCount = "places: 0 / 20";
        }else if ($day > $toDay){
            return $ticketCount = "places: " .$reservDate->getNumbers(). " / 20";
        }
        return new JsonResponse($ticketCount);
    }

    public function reservDate($em, $day)
    {
        return $em->getRepository('PROJETPlatformBundle:TicketCount')->findOneBy(['day' => $day]);
    }
    
}
