<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('default/checkout.html.twig', [
            'stripe_public_key' => $this->getStripeApiPublicKey()
        ]);
    }

    private function getStripeApiPublicKey(): String
    {
        return $this->getParameter('stripe_public_key');
    }
}
