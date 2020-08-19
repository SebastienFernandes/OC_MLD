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
use PROJET\PlatformBundle\Count\Check;
use PROJET\PlatformBundle\Count\Count;
use PROJET\PlatformBundle\SubmitForm\SubmitForm;
use PROJET\PlatformBundle\Billing\Billing;
use Doctrine\ORM\EntityManagerInterface;

class TicketController extends Controller
{
    public function indexAction($id, EntityManagerInterface $em)
    {
        $reservation    = $em->getRepository(Reservation::class)->find($id);
        $apiEmail       = $reservation->getEmail();
        $serviceBilling = $this->get(Billing::class);
        $totalPrice     = $serviceBilling->calculateTotalPrice($reservation);

        $day = $reservation->getDate();
        $test = date_format( $day, "m/d");
        $test2 = "06/30";
        if ("06/30" === $test){
            var_dump("true");
        }

        return $this->render('PROJETPlatformBundle:Reservation:index.html.twig', array(
            'reservation' => $reservation,
            'price' => $totalPrice,
            'tickets' => $reservation->getTickets()
        ));
    }

    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $reservation = new Reservation();
        $form        = $this->get('form.factory')->create(ReservationType::class, $reservation);

        $serviceCheck     = $this->get(Check::class);
        $ticketCountToDay = $serviceCheck->checkTicketCountToDay();

        $form->handleRequest($request);

        if ($request->isXmlHttpRequest())
        {
            $ticketCount = $serviceCheck->checkTicketCount($request);
            return new JsonResponse($ticketCount);
        }

        if (!$request->isXmlHttpRequest() && $form->isSubmitted() && $form->isValid()) {
            $serviceSubmitForm = $this->get(SubmitForm::class);

            try{
                $serviceSubmitForm->submit($request, $em, $reservation, $form);

                return $this->redirectToRoute('projet_platform_home', array('id' => $reservation->getId()));
            } catch(\InvalidArgumentException $exception) {
                $request->getSession()->getFlashBag()->add('info', $exception->getMessage());

                return $this->redirectToRoute('projet_platform_add');
            }
        }

        return $this->render('PROJETPlatformBundle:Reservation:add.html.twig', array(
          'form' => $form->createView(),
          'ticketCountToDay' => $ticketCountToDay
        ));
    }

    public function delAction($id, EntityManagerInterface $em)
    {
        $reservation    = $em->getRepository('PROJETPlatformBundle:Reservation')->find($id);
        $serviceCounter = $this->get(Count::class);
        $removeTicket   = $serviceCounter->removeTicketCounter($em, $reservation);

        foreach ($reservation->getTickets() as $ticket) {
            $em->remove($ticket);
        }

        if ($removeTicket->getNumbers() <= 0) {
            $em->remove($removeTicket);
        } else {
            $em->persist($removeTicket);
        }
        
        $em->remove($reservation);
        $em->flush();

        return $this->redirectToRoute('projet_core_homepage');
    }

    public function billingAction(Request $request, $id, EntityManagerInterface $em)
    {
        $reservation    = $em->getRepository('PROJETPlatformBundle:Reservation')->find($id);
        $serviceBilling = $this->get(Billing::class);
        $totalPrice     = $serviceBilling->calculateTotalPrice($reservation);

        if ($request->isMethod('POST')){
            $billing = $serviceBilling->billingAction($request, $totalPrice);            

            $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('fernandes91seb@gmail.com')
                ->setTo($reservation->getEmail())
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
            return $this->render('PROJETPlatformBundle:Reservation:billet.html.twig', array(
                'reservation' => $reservation
            ));
        }        

        return $this->render('PROJETPlatformBundle:Billing:bill.html.twig');
    }
}
