<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StartupInvestmentSource;
class StartupInvestmentSourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StartupInvestmentSource::create([
            'startup_id' => 1,
            'investment_source' => 'Business Angel'
        ]);

        StartupInvestmentSource::create([
            'startup_id' => 1,
            'investment_source' => 'VC Fund'
        ]);
    }
}
