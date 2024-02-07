<?php

namespace App\Jobs\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ProductService;
use App\Notifications\BulkDeleteProductJobFailedNotification;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\Log;

class BulkDeleteProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $title){}

    public function handle(ProductService $productService): void
    {
        $productService->destroyProductByTitle($this->title);
    }

     /**
     * Handle a job failure.
    */
    public function failed(Throwable $exception): void
    {
        $admin = User::find(1);
        $admin->notify(new BulkDeleteProductJobFailedNotification($admin, $this->title));
    }
}
