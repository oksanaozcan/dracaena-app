<?php

namespace Tests\Feature\Notifications;

use Tests\TestCase;
use App\Notifications\BulkDeleteTagJobFailedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use App\Models\User;

class BulkDeleteTagJobFailedNotificationTest extends TestCase
{
    public function test_1_notification_message_contains_correct_information()
    {
        $user = User::factory()->create(['name' => 'John']);
        $title = 'Sample Tag';
        $notification = new BulkDeleteTagJobFailedNotification($user, $title);

        $data = $notification->toArray($user);

        $this->assertArrayHasKey('message', $data);
        $this->assertEquals("Deleting by John tag with title $title failed. Check logs to define error.", $data['message']);
    }

    public function test_2_notification_is_sent_via_database_channel()
    {
        Notification::fake();

        $user = User::factory()->create();
        $title = 'Sample Tag';
        $notification = new BulkDeleteTagJobFailedNotification($user, $title);

        $user->notify($notification);

        Notification::assertSentTo(
            $user,
            BulkDeleteTagJobFailedNotification::class,
            function ($notification) use ($title) {
                return $notification->title === $title;
            }
        );
    }
}
