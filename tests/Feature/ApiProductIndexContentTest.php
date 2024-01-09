<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiProductIndexContentTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function test_1_it_should_test_the_content_of_the_json_response_list(){
        $arr = [];

        for ($i=0; $i < 8; $i++) {
            $p = Product::factory()->create([
                'title' => $this->faker->word().'Product',
                'description' => $this->faker->sentence,
                'content' => $this->faker->text,
                'preview' => $this->faker->imageUrl(),
                'price' => $this->faker->randomFloat(2, 10, 1000),
                'amount' => $this->faker->randomNumber(4),
                'category_id' => $this->faker->numberBetween(1, 5),
            ]);
            array_push($arr, $p);
        }

        $response = $this->getJson('/api/products?page=4');

        $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 4)
            ->has('links')
            ->has('meta')
                ->has('data.0', fn (AssertableJson $json) =>
                    $json->where('id', $arr[4]['id'])
                        ->where('title', $arr[4]['title'])
                        ->etc()
                )
                ->has('data.1', fn (AssertableJson $json) =>
                    $json->where('id', $arr[5]['id'])
                        ->where('title', $arr[5]['title'])
                        ->etc()
                )

        );
    }
}
