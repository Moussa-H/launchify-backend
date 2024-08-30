<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sector;

class SectorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
     {
        $sectors = [
            1 => 'Technology', 2 => 'Healthcare', 3 => 'Finance', 4 => 'Education',
            5 => 'Retail', 6 => 'Real Estate', 7 => 'Transportation', 8 => 'Energy',
            9 => 'Manufacturing', 10 => 'Agriculture', 11 => 'Media', 12 => 'Entertainment',
            13 => 'Telecommunications', 14 => 'Automotive', 15 => 'Logistics', 16 => 'Travel',
            17 => 'E-commerce', 18 => 'Sports', 19 => 'Biotech', 20 => 'Aerospace',
            21 => 'Chemicals', 22 => 'Construction', 23 => 'Food & Beverage', 24 => 'Gaming',
            25 => 'Health & Wellness', 26 => 'Security', 27 => 'Social Media', 28 => 'Fintech',
            29 => 'Proptech', 30 => 'Agtech', 31 => 'Cleantech', 32 => 'Insurtech',
            33 => 'Martech', 34 => 'Legaltech', 35 => 'HR Tech', 36 => 'Edtech',
            37 => 'Govtech', 38 => 'Medtech', 39 => 'Deep Tech', 40 => 'Sustainability',
            41 => 'SaaS', 42 => 'AI', 43 => 'AR/VR', 44 => 'Blockchain', 45 => 'Robotics',
            46 => 'IOT', 47 => 'Quantum Computing', 48 => '3D Printing', 49 => 'Automation',
            50 => 'Smart Cities', 51 => 'Finserv', 52 => 'Digital Health', 53 => 'Market Research',
            54 => 'Customer Service', 55 => 'Personal Finance', 56 => 'Business Services',
            57 => 'Cloud Computing', 58 => 'DevOps', 59 => 'IT Services', 60 => 'Digital Transformation'
        ];

        foreach ($sectors as $id => $name) {
            Sector::create(['id' => $id, 'name' => $name]);
        }
    }
}
