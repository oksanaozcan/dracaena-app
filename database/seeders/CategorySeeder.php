<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $categories = [
            ['title' => 'houseplants', 'preview' => $faker->imageUrl()],
            ['title' => 'pots', 'preview' => $faker->imageUrl()],
            ['title' => 'care', 'preview' => $faker->imageUrl()],
            ['title' => 'accessories', 'preview' => $faker->imageUrl()],
            ['title' => 'gifts', 'preview' => $faker->imageUrl()],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
