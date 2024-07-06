<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use App\Events\ProductOutOfStock;
use App\Events\ProductInStock;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    public $afterCommit = true;

    public $oldAmount;

    public function created(Product $product): void
    {
        Cache::tags('products')->flush();
    }

    public function updating(Product $product): void
    {
        $this->oldAmount = $product->amount;
    }

    public function updated(Product $product): void
    {
        if ($product->wasChanged('amount')) {

            if ($product->amount == 0) {
                event(new ProductOutOfStock($product));
            }

            if ($this->oldAmount == 0 && $product->amount > 0) {
                event(new ProductInStock($product));
            }
        }

        Cache::tags('products')->flush();
    }

    public function deleted(Product $product): void
    {
        Cache::tags('products')->flush();
    }
}
