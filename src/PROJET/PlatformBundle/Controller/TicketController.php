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
        $d    = new \Datetime();
        $d2   = date_format($d,'Y');
        $d3   = date_format($d,'md');
        $ddn2 = date_format($ddn,'Y');
        $ddn3 = date_format($ddn,'md');
        $age  = $d2 - $ddn2;
        if ($d3 < $ddn3){
            $age = $age - 1;
        }
//------------------------------------------------------------
        if ($age < 4){
            $rateType = 'free';
            $rate     = 0;
        }elseif (12 <= $age && $reduced == true){
            $rateType = 'reduced';
            $rate     = 10;            
        }elseif (4 <= $age && $age < 12){
            $rateType = 'child';
            $rate     = 8;
        }elseif (12 <= $age && $age < 60){
            $rateType = 'normal';
            $rate     = 16;
        }elseif (60 <= $age){
            $rateType = 'old';
            $rate     = 12;
        }

        if ($dayType == true){
            $rate = $rate / 2;
        }
//------------------------------------------------------------
        $ticket->setRateType($rateType);
        $ticket->setRate($rate);
        var_dump($rate);

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($ticket);
    	//$em->flush();

        return new response("ajout test!") ;
    }
}
