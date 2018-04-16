<?php

namespace PROJET\PlatformBundle\Count;

use PROJET\PlatformBundle\Entity\TicketCount;
use PROJET\PlatformBundle\Entity\Reservation;


class Count
{
    public function addTicketCounter($em, $reservation)
    {
        $count       = 0;
        $day         = $reservation->getDate();
        $countTicket = $em->getRepository('PROJETPlatformBundle:TicketCount')->findBy(array('day' => $day));

        foreach ($reservation->getTickets() as $ticket) {
            $count = $count + 1;
        }

        if (empty($countTicket)) {
            $ticketCount = new TicketCount();
            $ticketCount->setDay($day);
            $ticketCount->setNumbers($count);

            return $ticketCount;
        } else {
            $count1   = $countTicket[0]->getNumbers();
            $newCount = $count1 + $count;
            if($newCount > 20){
                return null;
            }else{
                $countTicket[0]->setNumbers($newCount);
                return $countTicket[0];
            }

            
        }
    }

    public function removeTicketCounter($em, $reservation)
    {
        $count       = 0;
        $day         = $reservation->getDate();
        $countTicket = $em->getRepository('PROJETPlatformBundle:TicketCount')->findBy(array('day' => $day));

        foreach ($reservation->getTickets() as $ticket) {
            $count = $count + 1;
        }

        $count1   = $countTicket[0]->getNumbers();
        $newCount = $count1 - $count;
        $countTicket[0]->setNumbers($newCount);

        return $countTicket[0];
    }
}
