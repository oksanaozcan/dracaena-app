<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Client::create([
        'clerk_id' => 'user_2XpxHHpFdmBrIPKS7qL3tnCnb2n',
      ]);
    }
}
