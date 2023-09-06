<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public $afterCommit = true;

    public function created(Product $product): void
    {
        // add product into cache redis if its property is_selling equel true
        // see functional laravel:
        // $user = User::withoutEvents(function () {
        //     User::findOrFail(1)->delete();

        //     return User::find(2);
        // });
    }

    public function updated(Product $product): void
    {
        //
    }

    public function deleted(Product $product): void
    {
        //
    }

    public function restored(Product $product): void
    {
        //
    }

    public function forceDeleted(Product $product): void
    {
        //
    }
}
