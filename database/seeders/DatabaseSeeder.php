<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Billboard;
use App\Models\BillboardTag;
use App\Models\Client;
use App\Utils\DBSeederHelper;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            ManagerSeeder::class,
            AssistantSeeder::class,
            CategoryFilterSeeder::class,
            TagSeeder::class,
        ]);

        Billboard::factory(5)->create();
        $this->call(ClientSeeder::class);

        $this->call([
            BillboardTagSeeder::class,
        ]);

      Product::factory(20)
        ->create()
        ->each(function ($product) {
            if ($product->category_id != 5) {
                $product->tags()->save(
                    ProductTag::factory()->make([
                        'product_id' => $product->id,
                        'tag_id' => DBSeederHelper::defineTagId($product->category_id)
                    ])
                );
            }
        });
    }
}
