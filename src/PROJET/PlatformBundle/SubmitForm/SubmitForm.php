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
            throw new \InvalidArgumentException("Il n'y a plus assé de places pour ce jour.");
        }

        $dayType = $reservation->getType();

        foreach ($reservation->getTickets() as $ticket) {
            $BirthDate = $ticket->getBirthDate();
            $reduced   = $ticket->getReducedPrice();
            $age       = $this->price->calculateAge($BirthDate);
            
            try {
                $rateType  = $this->price->calculateRateType($age, $reduced);
            }
            catch(\InvalidArgumentException $exception) {
                throw $exception;
            }

            $rate = $this->price->calculateRate($rateType, $dayType);
            $ticket->setRateType($rateType);
            $ticket->setRate($rate);
            $em->persist($ticket);
        }

        $em->persist($ticketCounter);
        $em->persist($reservation);
        $em->flush();

        return true;        
    }
    
}
