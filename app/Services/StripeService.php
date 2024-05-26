<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\PaymentPlatform;
use Debugbar;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        //
    }

    public function createCheckoutSession($lineItems, $successUrl, $cancelUrl)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        return \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }

    public function retrieveCheckoutSession($sessionId)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        return $stripe->checkout->sessions->retrieve($sessionId);
    }
}
