<?php

namespace App\Jobs\Tag;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TagService;
use Throwable;
use Illuminate\Support\Facades\Log;
use Barryvdh\Debugbar\Facades\Debugbar;

class StoreTagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public $title
    ){}

    public function handle(TagService $tagService): void
    {
        $tag = $tagService->storeTag($this->title);
    }

    public function failed(Throwable $exception): void
    {
        Log::info("failed from failed func job");
        // Debugbar::info("failed from failed func job");
        // Send user notification of failure, etc...

    }
}
