<?php

namespace Tests\Feature\Http\Livewire\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Database\Seeders\RoleSeeder;
use Tests\TestHelper;
use App\Http\Livewire\Order\Table;
use App\Http\Livewire\Order\Deleted;
use App\Models\Order;

class OrderDeletedTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->admin = $this->createUserWithRole("admin");
        $this->deletedOrder = $this->createSoftDeletedOrder();
        Livewire::actingAs($this->admin);
    }

    public function test_1_it_can_render_the_component()
    {
        $this->get('/deleted-orders')->assertSeeLivewire(Deleted::class);
    }

    public function test_2_it_can_not_render_the_table_component()
    {
        $this->get('/deleted-orders')->assertDontSeeLivewire(Table::class);
    }

    public function test_3_it_can_render_with_model()
    {
        Livewire::test(Deleted::class)
            ->assertSee($this->deletedOrder->id);
    }

    public function test_4_it_can_sort_orders()
    {
        $d1 = Order::factory()->create(['customer_name' => 'bbbbbbbbbb', 'deleted_at' => now()]);
        $d2 = Order::factory()->create(['customer_name' => 'aaaaaaaaaaaa', 'deleted_at' => now()]);

        Livewire::test(Deleted::class)
            ->call('sortBy', 'customer_name')
            ->assertSeeInOrder([$d2->customer_name, $d1->customer_name]);
    }

    public function test_5_it_emits_event_order_restored_and_calls_render_method()
    {
        Livewire::test(Deleted::class)
            ->assertSee($this->deletedOrder->id)
            ->call("restoreOrder", $this->deletedOrder->id)
            ->assertEmitted('orderRestored');

        $this->assertDatabaseHas('orders', ['id' => $this->deletedOrder->id]);
    }

    public function test_6_admin_can_force_delete_order()
    {
        Livewire::test(Deleted::class)
        ->assertSee($this->deletedOrder->id)
        ->call("forceDeleteOrder", $this->deletedOrder->id)
        ->assertEmitted('forceDeletedOrders');

        $this->assertDatabaseMissing('orders', ['id' => $this->deletedOrder->id]);
    }

    public function test_7_manager_can_not_force_delete_order()
    {
        $this->assertUserCanNotForceDeleteOrder("manager");
    }

    public function test_8_assistant_can_not_force_delete_order()
    {
        $this->assertUserCanNotForceDeleteOrder("assistant");
    }

    protected function assertUserCanNotForceDeleteOrder($role)
    {
        $user = $this->createUserWithRole($role);
        Livewire::actingAs($user);

        $res = Livewire::test(Deleted::class)
        ->call("forceDeleteOrder", $this->deletedOrder->id);

        $this->assertDatabaseHas('orders', ['id' => $this->deletedOrder->id]);
        $res->assertStatus(403);
    }
}
