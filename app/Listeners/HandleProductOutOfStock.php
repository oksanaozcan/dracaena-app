<?php

namespace App\Listeners;

use App\Events\ProductOutOfStock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;
use App\Models\Favourite;
use App\Mail\ProductOutOfStockMail;

class HandleProductOutOfStock
{
    public function __construct()
    {
        //
    }

    public function handle(ProductOutOfStock $event): void
    {
        $product = $event->product;

        $favouriteCustomers = Favourite::where('product_id', $product->id)
            ->with('customer')
            ->get()
            ->pluck('customer')
            ->unique('id');

        $cartCustomers = Cart::where('product_id', $product->id)
            ->with('customer')
            ->get()
            ->pluck('customer')
            ->unique('id');

        $customers = $favouriteCustomers->merge($cartCustomers)->unique('id');

        foreach ($customers as $customer) {
            if ($customer->newsletter_confirmed) {
                $customerName = $customer->name;
                Mail::to($customer->email)
                ->queue(new ProductOutOfStockMail($product, $customerName));
            }
        }

        Favourite::where('product_id', $product->id)->delete();
        Cart::where('product_id', $product->id)->delete();
    }
}
