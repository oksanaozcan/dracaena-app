<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cart;
use App\Http\Resources\ProductResource;
use Tests\TestHelper;

class CartTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createClient();
    }

    public function test_1_it_should_return_products_in_client_cart()
    {
        $data = $this->createCategoryAndProduct();
        $pr = $data['product'];

        Cart::create([
            'client_id' => $this->client->clerk_id,
            'product_id' => $pr->id,
        ]);

        $response = $this->getJson("api/carts/{$this->client->clerk_id}");

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                "data" => [
                    [
                        "id",
                        "title",
                        "description",
                        "content",
                        "preview",
                        "price",
                        "category",
                    ],
                ]
            ])
            ->assertJsonFragment([
                'id' => $pr->id,
                'title' => $pr->title,
            ]);
    }

    public function test_2_it_should_return_empty_array_for_client_with_no_products_in_cart()
    {
        $response = $this->getJson("api/carts/{$this->client->clerk_id}");

        $response
            ->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_3_it_should_return_404_for_nonexistent_client()
    {
        $nonExistentClientId = '999';

        $response = $this->getJson("api/carts/{$nonExistentClientId}");

        $response->assertStatus(404);
    }
}
