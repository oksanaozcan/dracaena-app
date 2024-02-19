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

class OrderTableTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->admin = $this->createUserWithRole("admin");
        $this->order = $this->createOrder();
        Livewire::actingAs($this->admin);
    }

    public function test_1_it_can_render_the_component()
    {
        $this->get('/orders')->assertSeeLivewire(Table::class);
    }

    public function test_2_it_can_not_render_the_deleted_component()
    {
        $this->get('/orders')->assertDontSeeLivewire(Deleted::class);
    }

    public function test_3_it_can_render_with_model()
    {
        Livewire::test(Table::class)
            ->assertSee($this->order->id);
    }

    public function test_4_it_can_sort_users()
    {
        $or1 = Order::factory()->create([
            'customer_name' => 'bbbbbbbbbbbbbbbbbbbbb',
        ]);
        $or2 = Order::factory()->create([
            'customer_name' => 'aaaaaaaaaaaaaaaaaaaa',
        ]);

        Livewire::test(Table::class)
            ->call('sortBy', 'customer_name')
            ->assertSeeInOrder([$or2->customer_name, $or1->customer_name]);
    }

    public function test_5_it_emits_event_order_soft_deleted_and_calls_render_method()
    {
        Livewire::test(Table::class)
            ->assertSee($this->order->id)
            ->call("destroyOrder", $this->order->id)
            ->assertEmitted('deletedOrders');

        $this->assertSoftDeleted('orders', [
            'id' => $this->order->id
        ]);
    }
}
