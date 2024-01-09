<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Cart;
use App\Http\Resources\ProductResource;

class CartTest extends TestCase
{
    use WithFaker;

    public function test_1_it_should_return_products_in_client_cart()
    {
        $cat = Category::factory()->create();
        $client = Client::factory()->create();
        $pr = Product::factory()->create([
            'title' => $this->faker->word().'Product',
            'description' => $this->faker->sentence,
            'content' => $this->faker->text,
            'preview' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'amount' => $this->faker->randomNumber(4),
            'category_id' => $cat->id,
        ]);

        Cart::create([
            'client_id' => $client->clerk_id,
            'product_id' => $pr->id,
        ]);

        $response = $this->getJson("api/carts/{$client->clerk_id}");

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
        $client = Client::factory()->create();

        $response = $this->getJson("api/carts/{$client->clerk_id}");

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
