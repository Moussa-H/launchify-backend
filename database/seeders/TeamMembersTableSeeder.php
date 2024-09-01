<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeamMember;
class TeamMembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
   {
        TeamMember::create([
            'startup_id' => 2,
            'fullname' => 'Jane Smith',
            'job_title' => 'CTO',
            'salary' => 120000
        ]);

        TeamMember::create([
            'startup_id' => 2,
            'fullname' => 'Alice Johnson',
            'job_title' => 'Lead Developer',
            'salary' => 100000
        ]);
    }
}
