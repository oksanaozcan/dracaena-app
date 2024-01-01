<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\Log;
use App\Models\Billboard;

class ApiBilboardTest extends TestCase
{
    public function test_without_params_1_it_should_test_the_count_of_the_json_response(){
        $response = $this->getJson('/api/billboards');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data');
    }

    public function test_without_params_2_it_should_test_the_structure_of_the_json_response(): void
    {
        $response = $this->getJson('/api/billboards');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'description',
                    'image',
                    'category_id',
                ],
            ]);
    }

    public function test_without_params_3_it_should_test_the_content_of_the_json_response(): void
    {
        $response = $this->getJson('/api/billboards');

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1,
                'category_id' => 1,
            ]);
    }
}
