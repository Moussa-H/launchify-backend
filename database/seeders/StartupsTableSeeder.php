<?php

namespace Database\Seeders;
use App\Models\Startup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StartupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Startup::factory()->create([
            'user_id' => 1,
            'company_name' => 'Startup Inc.',
            'description' => 'A leading innovator in AI and Blockchain technology, revolutionizing the industry.',
            'founder' => 'John Doe',
            'industry' => 'Technology',
            'founding_year' => 2020,
            'country' => 'USA',
            'city' => 'San Francisco',
            'key_challenges' => 'Scaling operations and securing Series A funding.',
            'goals' => 'To become the market leader in AI-powered solutions within the next 5 years.',
            'business_type' => 'B2B',
            'company_stage' => 'Seed',
            'employees_count' => 15,
            'phone_number' => '+1-800-555-1234',
            'email_address' => 'info@startupinc.com',
            'website_url' => 'https://www.startupinc.com',
            'currently_raising_type' => 'Seed',
            'currently_raising_size' => 2000000.00, // $2,000,000
        ]);
    }
}
