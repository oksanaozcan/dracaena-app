<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
        //Houseplants
            // by size
            ['title' => 'tubers and seeds', 'category_filter_id' => 1],
            ['title' => 'baby houseplants (S)', 'category_filter_id' => 1],
            ['title' => 'medium houseplants (M, L)', 'category_filter_id' => 1],
            ['title' => 'large houseplants (XL, XXL)', 'category_filter_id' => 1],

            //by plant family
            ['title' => 'calathea', 'category_filter_id' => 2],
            ['title' => 'monstera', 'category_filter_id' => 2],
            ['title' => 'alocasia', 'category_filter_id' => 2],
            ['title' => 'philodendron', 'category_filter_id' => 2],
            ['title' => 'anthurium', 'category_filter_id' => 2],
            ['title' => 'aglaonema', 'category_filter_id' => 2],

            //by placement area
            ['title' => 'bathroom plants', 'category_filter_id' => 3],
            ['title' => 'beedroom plants', 'category_filter_id' => 3],
            ['title' => 'livingroom plants', 'category_filter_id' => 3],
            ['title' => 'office plants', 'category_filter_id' => 3],
            ['title' => 'kitchen plants', 'category_filter_id' => 3],

            //by characteristic
            ['title' => 'easy houseplants', 'category_filter_id' => 4],
            ['title' => 'animal-friendly houseplants', 'category_filter_id' => 4],
            ['title' => 'air-purifying houseplants', 'category_filter_id' => 4],
            ['title' => 'shade houseplants', 'category_filter_id' => 4],
            ['title' => 'houseplant sets', 'category_filter_id' => 4],
            ['title' => 'hanging plants', 'category_filter_id' => 4],
            ['title' => 'rare plants', 'category_filter_id' => 4],

        //Pots
            //by size
            ['title' => 'baby pots (S)', 'category_filter_id' => 5],
            ['title' => 'medium pots (M, L)', 'category_filter_id' => 5],
            ['title' => 'large pots (XL, XXL)', 'category_filter_id' => 5],

            //by style
            ['title' => 'basic', 'category_filter_id' => 6],
            ['title' => 'nature', 'category_filter_id' => 6],
            ['title' => 'handmade', 'category_filter_id' => 6],
            ['title' => 'fun', 'category_filter_id' => 6],

            //by material
            ['title' => 'ceramic pots', 'category_filter_id' => 7],
            ['title' => 'braided baskets', 'category_filter_id' => 7],
            ['title' => 'terracotta pots', 'category_filter_id' => 7],
            ['title' => 'eco-plastic pots', 'category_filter_id' => 7],
            ['title' => 'nursery pots', 'category_filter_id' => 7],

            //pot accessories
            ['title' => 'plant stands', 'category_filter_id' => 8],
            ['title' => 'plant hangers', 'category_filter_id' => 8],
            ['title' => 'plant plateaus', 'category_filter_id' => 8],

        //Care
            //watering
            ['title' => 'watering cans', 'category_filter_id' => 9],
            ['title' => 'spray bottles', 'category_filter_id' => 9],
            ['title' => 'water meters', 'category_filter_id' => 9],

            //potting soil
            ['title' => 'potting soil', 'category_filter_id' => 10],
            ['title' => 'substrates', 'category_filter_id' => 10],
            ['title' => 'potting mix', 'category_filter_id' => 10],

            //propagation
            ['title' => 'propagation stations', 'category_filter_id' => 11],
            ['title' => 'cutting tools', 'category_filter_id' => 11],

            //growth
            ['title' => 'nutrition', 'category_filter_id' => 12],
            ['title' => 'grow lights', 'category_filter_id' => 12],
            ['title' => 'moss pole', 'category_filter_id' => 12],
            ['title' => 'plant stakes', 'category_filter_id' => 12],
            ['title' => 'cuttings', 'category_filter_id' => 12],
            ['title' => 'biological pest control', 'category_filter_id' => 12],
            ['title' => 'tools', 'category_filter_id' => 12],

        //Accessories
            //interior
            ['title' => 'tables', 'category_filter_id' => 13],
            ['title' => 'wall decoration', 'category_filter_id' => 13],

            //terrariums
            ['title' => 'terrariums', 'category_filter_id' => 14],
            ['title' => 'terrarium kits', 'category_filter_id' => 14],
            ['title' => 'DIY terrarium tools', 'category_filter_id' => 14],

            //candles
            ['title' => 'scented candles', 'category_filter_id' => 15],
            ['title' => 'dinner candles', 'category_filter_id' => 15],
            ['title' => 'pillar candles', 'category_filter_id' => 15],
            ['title' => 'fun candles', 'category_filter_id' => 15],
            ['title' => 'candle holders', 'category_filter_id' => 15],
            ['title' => 'all candlers and candle holders', 'category_filter_id' => 15],

            //others
            ['title' => 'books', 'category_filter_id' => 16],
            ['title' => 'plantbuddies', 'category_filter_id' => 16],
            ['title' => 'socks', 'category_filter_id' => 16],
            ['title' => 'DIY', 'category_filter_id' => 16],
            ['title' => 'dracaena merchandise', 'category_filter_id' => 16],

        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
