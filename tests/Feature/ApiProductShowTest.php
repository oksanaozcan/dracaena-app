<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Product;
use Tests\TestHelper;

class ApiProductShowTest extends TestCase
{
    use TestHelper;

    public function test_1_it_should_test_the_json_response()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data['product'];

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
