<?php

namespace App\Observers;

use App\Models\Cart;
use App\Models\Product;

class CartObserver
{
    /**
     * Handle the Cart "creating" event.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function creating(Cart $cart)
    {
        $product = Product::find($cart->product_id);

        if ($product && $product->amount == 0) {
            return false;
        }
    }
}
