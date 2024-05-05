<?php

namespace Database\Factories;

use App\Models\Favourite;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Product;

class FavouriteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Favourite::class;

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
