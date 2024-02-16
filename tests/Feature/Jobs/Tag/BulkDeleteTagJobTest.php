<?php

namespace Tests\Feature\Jobs\Tag;

use Tests\TestCase;
use App\Jobs\Tag\BulkDeleteTagJob;
use App\Services\TagService;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BulkDeleteTagJobFailedNotification;

class BulkDeleteTagJobTest extends TestCase
{
    public function test_1_job_is_constructed_correctly()
    {
        $title = 'Sample Tag';
        $job = new BulkDeleteTagJob($title);

        $this->assertEquals($title, $job->title);
    }

    public function test_2_handle_method_executes_destroyTagByTitle()
    {
        $title = 'Sample Tag';
        $tagService = $this->createMock(TagService::class);
        $tagService->expects($this->once())
            ->method('destroyTagByTitle')
            ->with($title);

        $job = new BulkDeleteTagJob($title);
        $job->handle($tagService);
    }

    public function test_3_failure_notification_sent_to_admin()
    {
        Notification::fake();

        $title = 'Sample Tag';
        $admin = User::find(1);
        $job = new BulkDeleteTagJob($title);
        $exception = new \Exception('Failed to delete tag');

        $job->failed($exception);

        Notification::assertSentTo(
            $admin,
            BulkDeleteTagJobFailedNotification::class,
            function ($notification) use ($title) {
                return $notification->title === $title;
            }
        );
    }
}
