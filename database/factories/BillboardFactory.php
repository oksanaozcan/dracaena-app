<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Billboard>
 */
class BillboardFactory extends Factory
{

    public function definition(): array
    {
        static $number = 1;

        return [
            'image' => $this->faker->imageUrl(),
            'category_id' => $number++,
            'description' => $this->faker->sentence,
        ];
    }
}
