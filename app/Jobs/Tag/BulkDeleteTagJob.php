<?php

namespace App\Jobs\Tag;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TagService;
use App\Notifications\JobFailedNotification;
use Illuminate\Notifications\Notification;

class BulkDeleteTagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $title){}

    public function handle(TagService $tagService): void
    {
        $tagService->destroyTagByTitle($this->title);
    }

    public function failed(Throwable $exception): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('id', 1);
        })->get();

        Notification::send($admins, new JobFailedNotification($exception));
    }
}
