<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tariffs')->insert([
            ['name' => 'Lite', 'price_per_user' => 4.00],
            ['name' => 'Starter', 'price_per_user' => 6.00],
            ['name' => 'Premium', 'price_per_user' => 10.00],
        ]);
    }
}
