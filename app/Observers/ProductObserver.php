<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use App\Events\ProductOutOfStock;

class ProductObserver
{
    public $afterCommit = true;

    public function created(Product $product): void
    {
        Cache::tags('products')->flush();
    }

    public function updated(Product $product): void
    {
        if ($product->amount == 0) {
            event(new ProductOutOfStock($product));
        }

        Cache::tags('products')->flush();
    }

    public function deleted(Product $product): void
    {
        Cache::tags('products')->flush();
    }
}
