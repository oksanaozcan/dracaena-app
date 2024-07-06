<?php

namespace App\Listeners;

use App\Events\ProductInStock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductInStockMail;
use App\Models\RestokeSubscription;

class HandleProductInStock
{
    public function __construct()
    {
        //
    }

    public function handle(ProductInStock $event): void
    {
        $product = $event->product;

        $subscriptions = RestokeSubscription::where('product_id', $product->id)->with('customer')->get();
        $customers = $subscriptions->map(function($subscription) {
            return $subscription->customer;
        });

        foreach ($customers as $customer) {
            Mail::to($customer->email)
            ->queue(new ProductInStockMail($product, $customer->name));
        }


    }
}
