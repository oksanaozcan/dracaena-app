<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $this->call(RoleSeeder::class);
      $this->call(AdminSeeder::class);

      Category::factory(10)->create();

      for ($i=0; $i < 200; $i++) {
        Product::factory()->hasTags(1)->create();
      }
    }
}
