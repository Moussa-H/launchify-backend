<?php

namespace Database\Factories;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
   protected $model = TeamMember::class;

    public function definition()
    {
        return [
            'startup_id' => \App\Models\Startup::factory(),
            'fullname' => $this->faker->name,
            'job_title' => $this->faker->word,
            'salary' => $this->faker->numberBetween(30000, 150000),
        ];
    }
}
