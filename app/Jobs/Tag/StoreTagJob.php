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
use App\Notifications\StoreTagJobFailedNotification;
use App\Models\User;

class StoreTagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public $title,
        public $category_filter_id,
        public $user
    ){}

    public function handle(TagService $tagService): void
    {
        $tagService->storeTag($this->title, $this->category_filter_id);
    }

    /**
     * Handle a job failure.
    */
    public function failed(Throwable $exception): void
    {
        $admin = User::find(1);
        $admin->notify(new StoreTagJobFailedNotification($this->user, $this->title));
    }
}
