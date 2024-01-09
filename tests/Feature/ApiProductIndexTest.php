<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiProductIndexTest extends TestCase
{
    use DatabaseTransactions;

    public function test_1_it_should_test_the_count_of_the_json_response_of_the_list_of_products_page_1(): void
    {
        $response = $this->getJson('/api/products?page=1');

        $response
            ->assertStatus(200)
            ->assertJsonCount(8, 'data');
    }

    public function test_2_it_should_test_the_structure_of_the_list_of_products_json_response_page_1(): void
    {
        $response = $this->getJson('/api/products?page=1');

        $response
            ->assertStatus(200)
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
        ]);
    }
}
