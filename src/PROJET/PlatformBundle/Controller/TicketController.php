<?php

namespace PROJET\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PROJET\PlatformBundle\Entity\Ticket;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        $ticket      = new Ticket();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $ticket);

        $formBuilder
            ->add('lastName',     TextType::class)
            ->add('firstName',    TextType::class)
            ->add('country',      TextType::class)
            ->add('birthDate',    DateType::class, array('widget' => 'choice'))
            ->add('type',         CheckboxType::class, array('required' => false))
            ->add('reducedPrice', CheckboxType::class, array('required' => false))
            ->add('email',        TextType::class)
            ->add('save',         SubmitType::class)
        ;

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')){
            $form->handleRequest($request);

            $price = $this->container->get('projet_platform.price');

            $ddn      = $ticket->getBirthDate();
            $reduced  = $ticket->getReducedPrice();
            $dayType  = $ticket->getType();
            $age      = $price->calculateAge($ddn);
            $rateType = $price->calculateRateType($age, $reduced);
            $rate     = $price->calculateRate($rateType, $dayType);

            $ticket->setRateType($rateType);
            $ticket->setRate($rate);

            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('projet_platform_home', array('id' => $ticket->getId()));
        }


        return $this->render('PROJETPlatformBundle:Ticket:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
