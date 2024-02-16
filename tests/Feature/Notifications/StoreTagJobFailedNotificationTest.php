<?php

namespace Tests\Feature\Notifications;

use Tests\TestCase;
use App\Notifications\StoreTagJobFailedNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class StoreTagJobFailedNotificationTest extends TestCase
{
    public function test_1_notification_message_contains_correct_information()
    {
        $user = User::factory()->create(['name' => 'John']);
        $title = 'Sample Tag';
        $notification = new StoreTagJobFailedNotification($user, $title);

        $data = $notification->toArray($user);

        $this->assertArrayHasKey('message', $data);
        $this->assertEquals("Storing by John tag with title $title failed. Check logs to define error.", $data['message']);
    }

    public function test_2_notification_is_sent_via_database_channel()
    {
        Notification::fake();

        $user = User::factory()->create();
        $title = 'Sample Tag';
        $notification = new StoreTagJobFailedNotification($user, $title);

        $user->notify($notification);

        Notification::assertSentTo(
            $user,
            StoreTagJobFailedNotification::class,
            function ($notification) use ($title) {
                return $notification->title === $title;
            }
        );
    }
}
