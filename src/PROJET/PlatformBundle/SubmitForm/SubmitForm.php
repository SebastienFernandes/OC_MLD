<?php

namespace PROJET\PlatformBundle\SubmitForm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PROJET\PlatformBundle\Price\Price;
use PROJET\PlatformBundle\Count\Count;


class SubmitForm
{
    private $price;
    private $count;

    public function __construct(Price $price, Count $count)
    {
        $this->price = $price;
        $this->count = $count;
    }

    public function submit($request, $em, $reservation, $form) 
    {
        $form->handleRequest($request);
        $ticketCounter = $this->count->addTicketCounter($em, $reservation);

        $day      = $reservation->getDate();
        $toDay    = (new \DateTime())->modify('-1 day');
        $dayl     = date_format( $day, "l");
        $monthDay = date_format( $day, "m/d");

        if ($day < $toDay){
            return 0;
        }
        else if (null === $ticketCounter){
            return 1;
        }
        else if ($dayl === "Tuesday"){
            return 3;
        }
        else if ($monthDay === "05/01" ||
                 $monthDay === "11/01" ||
                 $monthDay === "12/25"){
            return 3;
        }
        else if ($dayl === "Sunday"){
            return 4;
        }
        else if ($monthDay === "04/02" ||
                 $monthDay === "05/08" ||
                 $monthDay === "05/10" ||
                 $monthDay === "05/21" ||
                 $monthDay === "07/14" ||
                 $monthDay === "08/15" ||
                 $monthDay === "11/11"){
            return 4;
        }

        foreach ($reservation->getTickets() as $ticket) {
            $BirthDate = $ticket->getBirthDate();
            $reduced   = $ticket->getReducedPrice();
            $dayType   = $ticket->getType();
            $age       = $this->price->calculateAge($BirthDate);
            $rateType  = $this->price->calculateRateType($age, $reduced);
            $rate      = $this->price->calculateRate($rateType, $dayType);
            $ticket->setRateType($rateType);
            $ticket->setRate($rate);
            $em->persist($ticket);
        }

        $em->persist($ticketCounter);
        $em->persist($reservation);
        $em->flush();

        return 2;        
    }
    
}
