<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Product;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => function () {
                return Client::factory()->create()->clerk_id;
            },
            'product_id' => function () {
                return Product::factory()->create()->id;
            }
        ];
    }
}
