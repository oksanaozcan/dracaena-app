<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_1_can_create_order()
    {
        $order = Order::factory()->create();
        $this->assertInstanceOf(Order::class, $order);
    }

    public function test_2_order_belongs_to_client()
    {
        $client = Client::factory()->create();
        $order = Order::factory()->create(['client_id' => $client->clerk_id]);

        $this->assertInstanceOf(Client::class, $order->client);
        $this->assertEquals($client->clerk_id, $order->client->clerk_id);
    }

    public function test_3_order_can_have_products()
    {
        $order = Order::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $order->products()->attach([$product1->id, $product2->id], ['amount' => 2]);

        $this->assertInstanceOf(Product::class, $order->products->first());
        $this->assertEquals(2, $order->products->count());
    }
}
