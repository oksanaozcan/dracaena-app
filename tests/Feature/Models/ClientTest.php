<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Client;
use App\Models\Order;
use Tests\TestHelper;

class ClientTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    public function test_1_can_create_client()
    {
        $client = Client::factory()->create(['clerk_id' => 'stringfortestclientmodel']);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('stringfortestclientmodel', $client->clerk_id);
    }

    public function test_2_can_retrieve_orders_for_client()
    {
        $client = $this->createClient();
        $order = Order::factory()->create(['client_id' => $client->clerk_id]);

        $client->refresh();

        $this->assertTrue($client->orders->contains($order));
    }
}
