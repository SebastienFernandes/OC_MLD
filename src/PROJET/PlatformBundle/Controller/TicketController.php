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
    	$ticket->setBirthDate(new \Datetime('14-03-1988'));
    	$ticket->setType('type1');
    	$ticket->setReducedPrice(true);
    	$ticket->setEmail('Bob@email.com');

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($ticket);
    	$em->flush();

        return new response("ajout test!") ;
    }
}
