<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use Database\Seeders\RoleSeeder;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->order = $this->createOrder();
    }

    public function test_1_it_displays_the_order_index_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "orders.index", "order", "index");
    }

    public function test_2_it_does_not_display_the_order_index_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanNotAccessPage("manager", "orders.index", "index");
    }

    public function test_3_it_does_not_display_the_order_index_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanNotAccessPage("assistant", "orders.index","index");
    }

    public function test_4_it_displays_the_order_deleted_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "orders.deleted", "order", "deleted");
    }

    public function test_5_it_does_not_display_the_order_deleted_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanNotAccessPage("manager", "orders.deleted", "deleted");
    }

    public function test_6_it_does_not_display_the_order_deleted_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanNotAccessPage("assistant", "orders.deleted","deleted");
    }

    public function test_7_it_redirects_not_authenticated_users_from_index_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("orders.index");
    }

    public function test_8_it_redirects_not_authenticated_users_from_deleted_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("orders.deleted");
    }

    public function test_9_it_displays_the_show_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessShowPage("admin", $this->order, "orders.show", "order.show");
    }

    public function test_10_it_does_not_display_the_show_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanNotAccessShowPage("manager", $this->order, "orders.show", "order.show");
    }

    public function test_11_it_does_not_display_the_show_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanNotAccessShowPage("assistant", $this->order, "orders.show", "order.show");
    }

    public function test_12_it_redirects_not_authenticated_users_from_show_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("orders.show", $this->order);
    }

    public function test_13_it_allows_authorized_admin_users_to_delete_an_order()
    {
        $user = $this->createUserWithRole("admin");
        $this->actingAs($user)
            ->delete(route("orders.destroy", $this->order));

        $this->assertSoftDeleted('orders', [
            'id' => $this->order->id
        ]);
    }

    public function test_14_it_does_not_allow_authorized_manager_users_to_delete_an_order()
    {
        $this->assertRoleCanNotDeleteModel("manager", $this->order, "orders.destroy");
    }

    public function test_15_it_does_not_allow_authorized_assistant_users_to_delete_an_order()
    {
        $this->assertRoleCanNotDeleteModel("assistant", $this->order, "orders.destroy");
    }

    public function test_16_it_allows_authorized_admin_users_to_restore_an_order()
    {
        $user = $this->createUserWithRole("admin");
        $this->actingAs($user)
            ->post(route("orders.restore", $this->order->id));

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'deleted_at' => null,
        ]);
    }

    public function test_17_it_does_not_allow_authorized_manager_users_to_restore_an_order()
    {
        $this->roleCanNotAccessToRestoreOrder("manager");
    }

    public function test_18_it_does_not_allow_authorized_assistant_users_to_restore_an_order()
    {
        $this->roleCanNotAccessToRestoreOrder("assistant");
    }

    protected function roleCanNotAccessToRestoreOrder($role)
    {
        $deletedOrder = $this->createSoftDeletedOrder();
        $user = $this->createUserWithRole($role);
        $response = $this->actingAs($user)->post(route("orders.restore", $deletedOrder->id));
        $response->assertStatus(404);
    }
}
