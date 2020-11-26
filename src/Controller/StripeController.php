<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Stripe\Stripe;
use App\Services\StripeService;

/**
 * STRIPE PAYMENT METHOD INTEGRATION 
 * class StripeController
 * 
 * DOC : https://stripe.com/docs/payments/accept-a-payment?integration=elements
 */
class StripeController extends AbstractController
{
    /**
     * @Route(
     *  "/create-checkout-session", 
     *  name="stripe-create-checkout-session", 
     *  options={"expose"=true},
     *  methods={"GET","POST"} 
     *  )
     */
    public function create(
        StripeService $stripeService
        ): Response
    {
        $apiKey = $this->getStripeApiKey();
        Stripe::setApiKey($apiKey);

        $paymentMethodTypes = ['card'];
        $currency = 'eur';
        $productData =[
            'name' => 'HARLEME Robe sportswear',
        ];
        $unitAmount = 2000;
        $quantity = 1;
        $mode = 'payment';

        $path = [
            'success_url' => $this->generateUrl('stripe-successful-operation', [], UrlGeneratorInterface::ABSOLUTE_URL), 
            'cancel_url' => $this->generateUrl('stripe-error-operation', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ];

        $session = $stripeService->createStripeSession(
            $paymentMethodTypes,
            $currency,
            $productData,
            $unitAmount,
            $quantity,
            $mode,
            $path
        );

        return  $this->json(['id' => $session->id], 200);
    }

    /**
     * @Route("/successful-operation", name="stripe-successful-operation", methods={"GET"} )
     */
    public function success(): Response
    {
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/error-operation", name="stripe-error-operation", methods={"GET"} )
     */
    public function error() : Response
    {
        return $this->render('default/warning/errors.html.twig', []);
    }

    private function getStripeApiKey(): String
    {
        return $this->getParameter('stripe_secret_key');
    }
}
