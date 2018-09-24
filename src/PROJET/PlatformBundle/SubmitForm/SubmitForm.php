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
        $ticketCounter = $this->count->addTicketCounter($em, $reservation);

        if (null === $ticketCounter){
            return 1;
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
