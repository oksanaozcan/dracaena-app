<?php

namespace Tests\Feature\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class ApiNavigationTest extends TestCase
{
    public function test_1_it_should_test_the_count_of_the_json_response(): void
    {
        $response = $this->getJson('/api/navigation');

        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_2_it_should_test_the_structure_of_the_json_response(): void
    {
        $response = $this->getJson('/api/navigation');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    [
                        "id",
                        "title",
                        "preview",
                        "category_filters",
                        "tags",
                    ],
                ]
            ]);
    }

    public function test_3_it_should_test_the_content_of_the_json_response(): void
    {
        $response = $this->getJson('/api/navigation');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 5)
                    ->has('data.0', fn (AssertableJson $json) =>
                        $json->where('id', 1)
                            ->where('title', 'houseplants')
                            ->etc()
                    )
                    ->has('data.1', fn (AssertableJson $json) =>
                        $json->where('id', 2)
                            ->where('title', 'pots')
                            ->etc()
                    )
                    ->has('data.2', fn (AssertableJson $json) =>
                        $json->where('id', 3)
                            ->where('title', 'care')
                            ->etc()
                    )
                    ->has('data.3', fn (AssertableJson $json) =>
                        $json->where('id', 4)
                            ->where('title', 'accessories')
                            ->etc()
                    )
                    ->has('data.4', fn (AssertableJson $json) =>
                        $json->where('id', 5)
                            ->where('title', 'gifts')
                            ->etc()
                    )

        );
    }
}
