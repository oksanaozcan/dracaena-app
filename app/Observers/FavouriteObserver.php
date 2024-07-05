<?php

namespace App\Observers;

use App\Models\Favourite;
use App\Models\Product;

class FavouriteObserver
{
     /**
     * Handle the Favourite "creating" event.
     *
     * @param  \App\Models\Favourite  $favourite
     * @return void
     */
    public function creating(Favourite $favourite)
    {
        $product = Product::find($favourite->product_id);

        if ($product && $product->amount == 0) {
            return false;
        }
    }
}
