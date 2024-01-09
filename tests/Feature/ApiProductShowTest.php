<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Product;

class ApiProductShowTest extends TestCase
{
    use WithFaker;

    public function test_1_it_should_test_the_json_response()
    {
        $p = Product::factory()->create([
            'title' => $this->faker->word().'Product',
            'description' => $this->faker->sentence,
            'content' => $this->faker->text,
            'preview' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'amount' => $this->faker->randomNumber(4),
            'category_id' => $this->faker->numberBetween(1, 5),
        ]);

        $response = $this->getJson("/api/products/{$p->id}");

        $response
            ->assertStatus(200)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "title",
                    "description",
                    "content",
                    "preview",
                    "price",
                    "category",
                ],
            ])
            ->assertJsonFragment([
                'id' => $p->id,
                'title' => $p->title,
            ]);
    }

    public function test_2_it_should_return_404_if_product_does_not_exist()
    {
        // Choose an ID that doesn't exist in the database
        $nonExistentProductId = 999;

        $response = $this->getJson("/api/products/{$nonExistentProductId}");

        $response->assertStatus(404);
    }
}
