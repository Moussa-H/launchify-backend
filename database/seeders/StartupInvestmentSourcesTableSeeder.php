<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StartupInvestmentSource;
use App\Models\Startup;

class StartupInvestmentSourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure that a startup with ID 21 exists
         Startup::firstOrCreate(['id' => 2]);

        // Now create the investment source entry
        StartupInvestmentSource::create([
            'startup_id' => 2,
            'investment_source' => 'Business Angel'
        ]);
    }
}
