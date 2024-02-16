<?php

namespace Tests\Feature\Jobs\Notification;

use App\Jobs\Notification\RemoveReadedDbNotificationsJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\TestHelper;
use App\Models\DbNotification;
use Database\Seeders\RoleSeeder;

class RemoveReadedDbNotificationsJobTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->user = $this->createUserWithRole("admin");
    }

    public function test_it_removes_read_notifications_from_database()
    {
        $readednotification = DbNotification::factory()->create([
            'notifiable_id' => $this->user->id,
            'read_at' => now()
        ]);

        $notReadednotification = DbNotification::factory()->create([
            'notifiable_id' => $this->user->id,
        ]);

        $deletedId = $readednotification->id;


        $job = new RemoveReadedDbNotificationsJob([$readednotification->id]);
        $job->handle();

        $this->assertDatabaseMissing('notifications', ['id' => $deletedId]);
        $this->assertDatabaseHas('notifications', ['id' => $notReadednotification->id]);
    }
}
