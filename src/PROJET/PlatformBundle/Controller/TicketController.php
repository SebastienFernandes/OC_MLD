<?php

namespace PROJET\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PROJET\PlatformBundle\Entity\Ticket;
use PROJET\PlatformBundle\Entity\Reservation;
use PROJET\PlatformBundle\Entity\TicketCount;
use PROJET\PlatformBundle\Form\TicketType;
use PROJET\PlatformBundle\Form\ReservationType;

class TicketController extends Controller
{
    public function indexAction($id)
    {
    	$em          = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('PROJETPlatformBundle:Reservation')->find($id);
    	$apiEmail    = $reservation->getEmail();
        var_dump($apiEmail);

        foreach ($reservation->getTickets() as $ticket) {
            var_dump($ticket);
        }

        return $this->render('PROJETPlatformBundle:Reservation:index.html.twig', array('reservation' => $reservation));
    }

    public function addAction(Request $request)
    {
        $reservation = new Reservation();
        $form        = $this->get('form.factory')->create(ReservationType::class, $reservation);

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $price         = $this->container->get('projet_platform.price');
            $ticketCounter = $this->container->get('projet_platform.count');
            $em            = $this->getDoctrine()->getManager();
            $tc            = $ticketCounter->addTicketCounter($em, $reservation);            

            foreach ($reservation->getTickets() as $ticket) {
                $ddn      = $ticket->getBirthDate();
                $reduced  = $ticket->getReducedPrice();
                $dayType  = $ticket->getType();
                $age      = $price->calculateAge($ddn);
                $rateType = $price->calculateRateType($age, $reduced);
                $rate     = $price->calculateRate($rateType, $dayType);
                $ticket->setRateType($rateType);
                $ticket->setRate($rate);
                $em->persist($ticket);
            }

            $em->persist($tc);
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('projet_platform_home', array('id' => $reservation->getId()));
        }
        

        return $this->render('PROJETPlatformBundle:Reservation:add.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    public function delAction($id)
    {
        $em            = $this->getDoctrine()->getManager();
        $reservation   = $em->getRepository('PROJETPlatformBundle:Reservation')->find($id);
        $ticketCounter = $this->container->get('projet_platform.count');
        $tc            = $ticketCounter->removeTicketCounter($em, $reservation);

        foreach ($reservation->getTickets() as $ticket) {
            $em->remove($ticket);
        }

        if ($tc->getNumbers() <= 0) {
            $em->remove($tc);
        } else {
            $em->persist($tc);
        }
        
        $em->remove($reservation);
        $em->flush();

        return $this->redirectToRoute('projet_core_homepage');
    }
}
