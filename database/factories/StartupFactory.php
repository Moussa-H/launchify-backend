<?php

namespace Database\Factories;
use App\Models\Startup;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Startup>
 */
class StartupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Startup::class;


    public function definition(): array
    {
       return [
            'user_id' => \App\Models\User::factory(),
            'company_name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'founder' => $this->faker->name,
            'industry' => $this->faker->word,
            'founding_year' => $this->faker->year,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'key_challenges' => $this->faker->text,
            'goals' => $this->faker->text,
            'business_type' => $this->faker->randomElement(['B2B', 'B2C', 'B2B2C', 'B2G', 'C2C']),
            'company_stage' => $this->faker->randomElement(['Idea', 'Pre-seed', 'Seed', 'Early Growth', 'Growth', 'Maturity']),
            'employees_count' => $this->faker->numberBetween(1, 100),
            'phone_number' => $this->faker->phoneNumber,
            'email_address' => $this->faker->email,
            'website_url' => $this->faker->url,
            'currently_raising_type' => $this->faker->randomElement(['Founders', 'Family & Friends', 'Pre-seed', 'Seed', 'Pre-series A', 'Series A']),
            'currently_raising_size' => $this->faker->randomFloat(2, 100000, 10000000),
        ];
    }
}
