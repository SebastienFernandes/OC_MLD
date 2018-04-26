<?php

namespace PROJET\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $price = 0;

        foreach ($reservation->getTickets() as $ticket) {
            $price = $price + $ticket->getRate();
        }

        return $this->render('PROJETPlatformBundle:Reservation:index.html.twig', array(
            'reservation' => $reservation,
            'price' => $price,
            'tickets' => $reservation->getTickets()
        ));
    }

    public function addAction(Request $request)
    {
        $em          = $this->getDoctrine()->getManager();
        $reservation = new Reservation();
        $form        = $this->get('form.factory')->create(ReservationType::class, $reservation);

        $serviceCheck     = $this->container->get('projet_platform.check');
        $ticketCountToDay = $serviceCheck->checkTicketCountToDay($em);

        if ($request->isMethod('POST') && !$request->isXmlHttpRequest()) {
            $serviceSubmitForm = $this->container->get('projet_platform.submitForm');
            $submitForm        = $serviceSubmitForm->submit($request, $em, $reservation, $form);
            return $this->redirectToRoute('projet_platform_home', array('id' => $reservation->getId()));
        }

        if ($request->isXmlHttpRequest())
        {            
            $ticketCount = $serviceCheck->checkTicketCount($request, $em);
            return new JsonResponse($ticketCount);
        }        

        return $this->render('PROJETPlatformBundle:Reservation:add.html.twig', array(
          'form' => $form->createView(),
          'ticketCountToDay' => $ticketCountToDay
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

    public function billingAction(Request $request, $id)
    {
        $em          = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('PROJETPlatformBundle:Reservation')->find($id);
        $price = 0;

        foreach ($reservation->getTickets() as $ticket) {
            $price = $price + $ticket->getRate();
        }

        if ($request->isMethod('POST')){
            \Stripe\Stripe::setApiKey("sk_test_RGoO7ycfZELVst0lBoTE4UhK");
            $token = $_POST['stripeToken'];
            $charge = \Stripe\Charge::create(array(
            "amount" => $price,
            "currency" => "eur",
            "description" => "Example charge",
            "source" => $request->request->get('stripeToken'),
            ));

            $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('fernandes91seb@gmail.com')
                ->setTo('fernandes91seb@gmail.com')
                ->setBody(
                    $this->renderView(
                        'Emails/email.html.twig',
                        array('reservation' => $reservation)
                    )
                )
                ->attach(
                    \Swift_Attachment::fromPath('https://upload.wikimedia.org/wikipedia/commons/a/a2/EAN-13-5901234123457.svg')
                        ->setDisposition('inline')
                );

            $this->get('mailer')->send($message);
            return $this->render('Emails/email.html.twig', array(
                'reservation' => $reservation
            ));
        }
        

        return $this->render('PROJETPlatformBundle:Billing:bill.html.twig');
    }
}
