<?php

namespace Tests\Feature\Jobs\Tag;

use App\Jobs\Tag\StoreTagJob;
use App\Notifications\StoreTagJobFailedNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StoreTagJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_failed_notification_sent_to_admin(): void
    {
        Notification::fake();

        // Mocking the admin user
        $admin = User::find(1);

        // Creating a new instance of the StoreTagJob with sample data
        $user = User::factory()->create();
        $job = new StoreTagJob('Sample Tag Title', 1, $user);

        // Calling the failed method directly with a sample exception
        $job->failed(new \Exception('Sample exception'));

        // Asserting that the admin user was notified with the StoreTagJobFailedNotification
        Notification::assertSentTo(
            $admin,
            StoreTagJobFailedNotification::class,
            function ($notification, $channels) use ($job) {
                return $notification->title === $job->title;
            }
        );
    }
}
