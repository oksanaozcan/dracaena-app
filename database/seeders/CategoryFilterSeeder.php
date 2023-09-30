<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryFilter;

class CategoryFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryFilters = [
            ['title' => 'by size', 'category_id' => 1],
            ['title' => 'by plant family', 'category_id' => 1],
            ['title' => 'by placement area', 'category_id' => 1],
            ['title' => 'by characteristic', 'category_id' => 1],

            ['title' => 'by size', 'category_id' => 2],
            ['title' => 'by style', 'category_id' => 2],
            ['title' => 'by material', 'category_id' => 2],
            ['title' => 'pot accessories', 'category_id' => 2],

            ['title' => 'watering', 'category_id' => 3],
            ['title' => 'potting soil', 'category_id' => 3],
            ['title' => 'propagation', 'category_id' => 3],
            ['title' => 'growth', 'category_id' => 3],

            ['title' => 'interior', 'category_id' => 4],
            ['title' => 'terrariums', 'category_id' => 4],
            ['title' => 'candles', 'category_id' => 4],
            ['title' => 'others', 'category_id' => 4],
        ];

        foreach ($categoryFilters as $categoryFilter) {
            CategoryFilter::create($categoryFilter);
        }
    }
}
