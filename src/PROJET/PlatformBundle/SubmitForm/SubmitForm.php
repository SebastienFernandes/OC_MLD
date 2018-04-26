<?php

namespace PROJET\PlatformBundle\SubmitForm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SubmitForm
{
	private $price;
	private $count;

	public function __construct($price, $count)
	{
		$this->price = $price;
		$this->count = $count;
	}

    public function submit($request, $em, $reservation, $form) 
    {
        $form->handleRequest($request);
        $ticketCounter        = $this->count->addTicketCounter($em, $reservation);
        if (null === $ticketCounter){
            return false;
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

        return true;        
    }
    
}
