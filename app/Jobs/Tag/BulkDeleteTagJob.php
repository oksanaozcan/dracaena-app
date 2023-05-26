<?php

namespace App\Jobs\Tag;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TagService;
use App\Notifications\BulkDeleteTagJobFailedNotification;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\Log;

class BulkDeleteTagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $title){}

    public function handle(TagService $tagService): void
    {
        $tagService->destroyTagByTitle($this->title);
    }

     /**
     * Handle a job failure.
    */
    public function failed(Throwable $exception): void
    {
        $admin = User::find(1);
        $admin->notify(new BulkDeleteTagJobFailedNotification($admin, $this->title));
    }
}
