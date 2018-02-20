<?php

namespace PROJET\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use PROJET\PlatformBundle\Entity\Ticket;

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
    	$api = $ticket;
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
//------------------------------------------------------------
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('PROJETPlatformBundle:Ticket')
        ;

        $age      = $repository->calculateAge($ddn);
        $rateType = $repository->calculateRateType($age, $reduced);
        $rate     = $repository->calculateRate($rateType, $dayType);
//------------------------------------------------------------
        $ticket->setRateType($rateType);
        $ticket->setRate($rate);
        var_dump($rateType);
        var_dump($rate);

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($ticket);
    	//$em->flush();

        return new response("ajout test!") ;
    }
}
