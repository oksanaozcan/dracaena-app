<?php

namespace Tests\Feature\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestHelper;

class ApiProductIndexContentTest extends TestCase
{
    use TestHelper;

    public function test_1_it_should_test_the_content_of_the_json_response_list(){
        $arr = [];

        for ($i=0; $i < 8; $i++) {
            $data = $this->createCategoryAndProduct();
            $p = $data['product'];
            array_push($arr, $p);
        }

        $response = $this->getJson('/api/products?page=5');

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
