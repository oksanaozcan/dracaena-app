<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BillboardTag;
use Faker\Factory as Faker;

class BillboardTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $billboardtags = [
            ['billboard_id' => 2, 'tag_id' => 30],
            ['billboard_id' => 3, 'tag_id' => 38],
        ];

        foreach ($billboardtags as $bt) {
            BillboardTag::create($bt);
        }
    }
}
