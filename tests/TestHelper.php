<?php

namespace Tests;

use App\Models\Category;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;

trait TestHelper
{
    use WithFaker;
    
    protected function createCategoryAndProduct(): array
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'title' => $this->faker->word() . 'Product',
            'description' => $this->faker->sentence,
            'content' => $this->faker->text,
            'preview' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'amount' => $this->faker->randomNumber(4),
            'category_id' => $category->id,
        ]);

        return ['category' => $category, 'product' => $product];
    }

    protected function createClient(): Client
    {
        $client = Client::factory()->create();
        return $client;
    }
}
