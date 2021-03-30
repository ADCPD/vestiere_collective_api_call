<?php

namespace App\Services;


use Stripe\Checkout\Session AS StripeSession;

class StripeService {

    public function createStripeSession(
        array $paymentMethodTypes,
        string $currency = 'eur',
        array $productData,
        int $unitAmount,
        int $quantity = 1,
        string $mode = 'payment',
        array $path
        )
    {
        $session = StripeSession::create([
            'payment_method_types' => $paymentMethodTypes,
            'line_items' => [[
              'price_data' => [
                'currency' => $currency,
                'product_data' => $productData,
                'unit_amount' => $unitAmount,
              ],
              'quantity' => $quantity,
            ]],
            'mode' => $mode,
            'success_url' => key_exists('success_url', $path) ? $path['success_url'] : '',
            'cancel_url' => key_exists('cancel_url', $path) ? $path['cancel_url'] : '',
          ]);

        return $session;
    }
}
