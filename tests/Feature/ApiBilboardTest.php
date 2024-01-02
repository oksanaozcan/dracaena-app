<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Billboard;

class ApiBilboardTest extends TestCase
{
    public function test_without_params_1_it_should_test_the_count_of_the_json_response(): void
    {
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

    public function test_with_categoryid2_4_it_should_test_the_json_response(){
        $response = $this->getJson('/api/billboards?category_id=2');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'description',
                    'image',
                    'category_id',
                ],
            ])
            ->assertJsonFragment([
                'id' => 2,
                'category_id' => 2,
            ]);
    }

    public function test_with_tagid_5_it_should_test_the_json_response_if_billboard_for_this_tag_exists(){
        $b = Billboard::factory()->create([
            'image' => "https://via.placeholder.com/640x480.png/005500?text=perferendis",
            'category_id' => 2,
            'description' => "this billboard created for testing json response if this model for tag_id params exists",
        ]);
        $b->tags()->attach([23]);

        $response = $this->getJson('/api/billboards?tag_id=23');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'description',
                    'image',
                    'category_id',
                ],
            ])
            ->assertJsonFragment([
                'id' => 6,
                'category_id' => 2,
            ]);
    }

    public function test_with_tagid_6_it_should_test_the_json_response_if_billboard_for_this_tag_does_not_exist(){

        $response = $this->getJson('/api/billboards?tag_id=24');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'description',
                    'image',
                    'category_id',
                ],
            ])
            ->assertJsonFragment([
                'id' => 2,
                'category_id' => 2,
            ]);
    }
}
