<?php

namespace Tests\Feature\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;

class OrderApiControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    public function test_1_checkout()
    {
        $data = $this->createProductAndPutItInCartOfClient();
        $client = $data['client'];
        $pr = $data['product'];

        $response = $this->postJson('/api/checkout', [
            'clientId' => $client->clerk_id,
            'productIds' => [$pr->id],
        ]);

        $response->assertSuccessful()
            ->assertJsonStructure(['url']);
    }
}
