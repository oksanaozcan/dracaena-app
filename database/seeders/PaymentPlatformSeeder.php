<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PaymentPlatform;
use Illuminate\Database\Seeder;

class PaymentPlatformSeeder extends Seeder
{
    public function run(): void
    {
        PaymentPlatform::create([
            'name' => 'Stripe',
            'is_subscription_enabled' => false
        ]);
    }
}
