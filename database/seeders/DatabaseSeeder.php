<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Product;
use App\Models\Billboard;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $this->call([
        RoleSeeder::class,
        AdminSeeder::class,
        ManagerSeeder::class,
        AssistantSeeder::class,
      ]);

      Category::factory(10)->create();
      Billboard::factory(5)->create();

      for ($i=0; $i < 40; $i++) {
        Product::factory()->hasTags(1)->create();
      }
    }
}
