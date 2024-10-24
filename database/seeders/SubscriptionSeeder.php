<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            [
                'status' => 'active',
                'tariff_id' => 1, // Assuming Lite has id 1
                'user_count' => 7,
                'total_cost' => 28.00,
                'payment_frequency' => 'monthly',
                'valid_until' => Carbon::create(2024, 10, 20)->format('Y-m-d'),
            ],
        ]);
    }
}
