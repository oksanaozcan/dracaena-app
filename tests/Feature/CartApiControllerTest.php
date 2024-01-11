<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Client;
use Tests\TestHelper;

class CartApiControllerTest extends TestCase
{
    use TestHelper;

    public function test_1_it_should_test_storing_success(): void
    {
        $client = $this->createClient();
        $data = $this->createCategoryAndProduct();

        $requestData = [
            'product_id' => $data['product']->id,
            'client_id' => $client->clerk_id,
        ];

        $response = $this->post('/api/carts', $requestData);

        $response->assertStatus(201)
             ->assertJson(['message' => 'Cart item added successfully']);
    }
}
