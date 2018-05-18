<?php

namespace PROJET\PlatformBundle\Billing;


class Billing
{
    public function calculateTotalPrice($reservation)
    {
        $price = 0;
        foreach ($reservation->getTickets() as $ticket) {
            $price = $price + $ticket->getRate();
        }
        return $price;
    }

    public function billingAction($request, $totalPrice)
    {
        \Stripe\Stripe::setApiKey("sk_test_RGoO7ycfZELVst0lBoTE4UhK");
        $token = $_POST['stripeToken'];
        $charge = \Stripe\Charge::create(array(
        "amount" => $totalPrice,
        "currency" => "eur",
        "description" => "Example charge",
        "source" => $request->request->get('stripeToken'),
        ));
    }
}