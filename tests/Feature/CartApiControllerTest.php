<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Client;
use App\Models\Cart;
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

    public function test_2_it_should_test_storing_failure_if_product_does_not_exists(): void
    {
        $client = $this->createClient();
        $nonExistentProductId = '99999';

        $requestData = [
            'product_id' => $nonExistentProductId,
            'client_id' => $client->clerk_id,
        ];

        $response = $this->post('/api/carts', $requestData);

        $response->assertStatus(500)
            ->assertJson(['error' => 'Cart item addition failed']);
    }

    public function test_3_it_should_test_storing_failure_if_client_does_not_exists(): void
    {
        $nonExistentClientId = '99999';
        $data = $this->createCategoryAndProduct();

        $requestData = [
            'product_id' => $data['product']->id,
            'client_id' => $nonExistentClientId,
        ];

        $response = $this->post('/api/carts', $requestData);

        $response->assertStatus(500)
            ->assertJson(['error' => 'Cart item addition failed']);
    }

    public function test_4_it_should_test_deleting_success(): void
    {
        $client = $this->createClient();
        $data = $this->createCategoryAndProduct();

        Cart::create([
            'client_id' => $client->clerk_id,
            'product_id' => $data['product']->id,
        ]);

        $requestData = [
            'productId' => $data['product']->id,
            'userId' => $client->clerk_id,
        ];

        $response = $this->delete('/api/carts', $requestData);

        $response->assertStatus(201)
             ->assertJson(['message' => 'Cart item deleted successfully']);
    }

    public function test_5_it_should_test_deleting_failure_if_cart_item_does_not_exists(): void
    {
        $client = $this->createClient();
        $data = $this->createCategoryAndProduct();

        $requestData = [
            'productId' => $data['product']->id,
            'userId' => $client->clerk_id,
        ];

        $response = $this->delete('/api/carts', $requestData);

        $response->assertStatus(500)
        ->assertJson(['error' => 'Cart item deletion failed']);
    }


}
