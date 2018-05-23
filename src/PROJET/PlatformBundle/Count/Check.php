<?php

namespace PROJET\PlatformBundle\Count;

use PROJET\PlatformBundle\Entity\TicketCount;


class Check
{
    public function checkTicketCountToDay($em) 
    {
        $day = new \DateTime();
        $day->setTime(00, 00, 00);
        $reservDate = $this->reservDate($em, $day);
        if ($reservDate === null){
            return $ticketCountToDay = 0;
        }else{
            return $ticketCountToDay = $reservDate->getNumbers();
        }
    }

    public function checkTicketCount($request, $em) 
    {
        $m          = $request->request->get('month');
        $d          = $request->request->get('day');
        $y          = $request->request->get('year');
        $day        = new \DateTime($y .'/'. $m .'/'. $d);
        $toDay      = (new \DateTime())->modify('-1 day');
        $reservDate = $this->reservDate($em, $day);

        if ($day < $toDay){
            return $ticketCount = "<style> #places{color : red; font-weight: bold;}</style>  Attention date non valide! ";
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
