<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use Database\Seeders\RoleSeeder;
use App\Models\User;
use App\Models\DbNotification;

class DbNotificationControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_1_index_method_returns_view_for_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "notifications", null, "notifications");
    }

    public function test_2_index_method_returns_view_for_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "notifications", null, "notifications");
    }

    public function test_3_index_method_returns_view_for_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "notifications", null, "notifications");
    }

    public function test_4_mark_as_read_method_marks_notification_as_read()
    {
        $user = User::factory()->admin()->create();
        $notification = DbNotification::factory()->create([
            'notifiable_id' => $user->id
        ]);

        $response = $this->actingAs($user)->get("/markasread/{$notification->id}");

        $this->assertDatabaseHas('notifications', ['id' => $notification->id]);
        $this->assertNotNull(DbNotification::find($notification->id)->read_at);

        $response->assertRedirect();
    }

    public function test_5_mark_as_read_method_redirects_back_when_no_id_provided()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)
                         ->get('/markasread');

        $response->assertStatus(302);
        $response->assertRedirect();
    }
}
