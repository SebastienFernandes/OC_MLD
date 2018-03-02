<?php

namespace PROJET\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PROJET\PlatformBundle\Entity\Ticket;
use PROJET\PlatformBundle\Entity\Reservation;
use PROJET\PlatformBundle\Form\TicketType;
use PROJET\PlatformBundle\Form\ReservationType;

class TicketController extends Controller
{
    public function indexAction($id)
    {
    	$repository = $this
	        ->getDoctrine()
	        ->getManager()
	        ->getRepository('PROJETPlatformBundle:Ticket')
      	;

      	$ticket = $repository->find($id);
    	$api    = $ticket;
        return new response(var_dump($api)) ;
    }

    public function addTestAction()
    {
    	$ticket = new Ticket();
    	$ticket->setLastName('TAUNT');
    	$ticket->setFirstName('Bob');
    	$ticket->setCountry('France');
    	$ticket->setBirthDate($ddn = new \Datetime('14-01-1988'));
    	$ticket->setType($dayType = false);
    	$ticket->setReducedPrice($reduced = false);
    	$ticket->setEmail('Bob@email.com');

        $price = $this->container->get('projet_platform.price');

        $age      = $price->calculateAge($ddn);
        $rateType = $price->calculateRateType($age, $reduced);
        $rate     = $price->calculateRate($rateType, $dayType);

        $ticket->setRateType($rateType);
        $ticket->setRate($rate);
        var_dump($rateType);
        var_dump($rate);

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($ticket);
    	//$em->flush();

        return new Response("ajout test!") ;
    }

    public function addAction(Request $request)
    {
        $reservation = new Reservation();

        $form = $this->get('form.factory')->create(ReservationType::class, $reservation);

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $price = $this->container->get('projet_platform.price');
            $em = $this->getDoctrine()->getManager();

            foreach ($reservation->getTickets() as $ticket) {
                $ddn      = $ticket->getBirthDate();
                $reduced  = $ticket->getReducedPrice();
                $dayType  = $ticket->getType();
                $age      = $price->calculateAge($ddn);
                $rateType = $price->calculateRateType($age, $reduced);
                $rate     = $price->calculateRate($rateType, $dayType);
                $ticket->setRateType($rateType);
                $ticket->setRate($rate);
                var_dump($ticket);
                $em->persist($ticket);
            }

            
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('projet_platform_home');
        }
        

        return $this->render('PROJETPlatformBundle:Reservation:add.html.twig', array(
          'form' => $form->createView(),
        ));
    }
}
