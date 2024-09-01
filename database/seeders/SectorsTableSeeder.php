<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sector;
use Illuminate\Support\Facades\DB;

class SectorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing records
        DB::table('sectors')->delete();

        // Define the sectors with the proper structure
        $sectors = [
            ['id' => 1, 'name' => 'Technology'],
            ['id' => 2, 'name' => 'Healthcare'],
            ['id' => 3, 'name' => 'Finance'],
            ['id' => 4, 'name' => 'Education'],
            ['id' => 5, 'name' => 'Retail'],
            ['id' => 6, 'name' => 'Real Estate'],
            ['id' => 7, 'name' => 'Transportation'],
            ['id' => 8, 'name' => 'Energy'],
            ['id' => 9, 'name' => 'Manufacturing'],
            ['id' => 10, 'name' => 'Agriculture'],
            ['id' => 11, 'name' => 'Media'],
            ['id' => 12, 'name' => 'Entertainment'],
            ['id' => 13, 'name' => 'Telecommunications'],
            ['id' => 14, 'name' => 'Automotive'],
            ['id' => 15, 'name' => 'Logistics'],
            ['id' => 16, 'name' => 'Travel'],
            ['id' => 17, 'name' => 'E-commerce'],
            ['id' => 18, 'name' => 'Sports'],
            ['id' => 19, 'name' => 'Biotech'],
            ['id' => 20, 'name' => 'Aerospace'],
            ['id' => 21, 'name' => 'Chemicals'],
            ['id' => 22, 'name' => 'Construction'],
            ['id' => 23, 'name' => 'Food & Beverage'],
            ['id' => 24, 'name' => 'Gaming'],
            ['id' => 25, 'name' => 'Health & Wellness'],
            ['id' => 26, 'name' => 'Security'],
            ['id' => 27, 'name' => 'Social Media'],
            ['id' => 28, 'name' => 'Fintech'],
            ['id' => 29, 'name' => 'Proptech'],
            ['id' => 30, 'name' => 'Agtech'],
            ['id' => 31, 'name' => 'Cleantech'],
            ['id' => 32, 'name' => 'Insurtech'],
            ['id' => 33, 'name' => 'Martech'],
            ['id' => 34, 'name' => 'Legaltech'],
            ['id' => 35, 'name' => 'HR Tech'],
            ['id' => 36, 'name' => 'Edtech'],
            ['id' => 37, 'name' => 'Govtech'],
            ['id' => 38, 'name' => 'Medtech'],
            ['id' => 39, 'name' => 'Deep Tech'],
            ['id' => 40, 'name' => 'Sustainability'],
            ['id' => 41, 'name' => 'SaaS'],
            ['id' => 42, 'name' => 'AI'],
            ['id' => 43, 'name' => 'AR/VR'],
            ['id' => 44, 'name' => 'Blockchain'],
            ['id' => 45, 'name' => 'Robotics'],
            ['id' => 46, 'name' => 'IOT'],
            ['id' => 47, 'name' => 'Quantum Computing'],
            ['id' => 48, 'name' => '3D Printing'],
            ['id' => 49, 'name' => 'Automation'],
            ['id' => 50, 'name' => 'Smart Cities'],
            ['id' => 51, 'name' => 'Finserv'],
            ['id' => 52, 'name' => 'Digital Health'],
            ['id' => 53, 'name' => 'Market Research'],
            ['id' => 54, 'name' => 'Customer Service'],
            ['id' => 55, 'name' => 'Personal Finance'],
            ['id' => 56, 'name' => 'Business Services'],
            ['id' => 57, 'name' => 'Cloud Computing'],
            ['id' => 58, 'name' => 'DevOps'],
            ['id' => 59, 'name' => 'IT Services'],
            ['id' => 60, 'name' => 'Digital Transformation'],
        ];

        // Insert each sector into the database
        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
