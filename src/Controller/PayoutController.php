<?php

namespace App\Controller;

use App\Services\CurrencyApiService;
use App\Services\PayoutService;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PayoutController
 * @package App\Controller
 */
class PayoutController extends AbstractController
{
    /**
     * Exemple http://localhost:88/item/payout?seller_reference=7&items="3,5"&currency=USD
     *
     * @Route("/item/payout", name="payout_items")
     * @Method("POST")
     * @param Request $request
     * @param PayoutService $payoutService
     * @param CurrencyApiService $currencyApiService
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function payout(
       Request $request,
        PayoutService $payoutService,
       CurrencyApiService $currencyApiService
    ): Response
    {
        $sellerReference = $request->get('seller_reference');
        $items = $request->get('items');
        $currency = $request->get('currency');

        $apiKey = $this->getStripeApiKey();
        Stripe::setApiKey($apiKey);
        $path = [
            'success_url' => $this->generateUrl('stripe-successful-operation', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('stripe-error-operation', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ];

        $currencyApi = $this->getParameter('currency_api_path');
        $data = [
            "seller_id"       => $sellerReference,
            "items"           => $items,
            "currencyAmount"  => $currencyApiService->getCurrencyAmount($currencyApi, $currency),
            "currency"        => $currency,
            "paths"           => $path
        ];

        return $this->json(
            $payoutService->paymentProcess($data)
        );
    }

    /**
     * @return String
     */
    private function getStripeApiKey(): String
    {
        return $this->getParameter('stripe_secret_key');
    }
}
