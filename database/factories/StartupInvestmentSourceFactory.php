<?php

namespace Database\Factories;
use App\Models\StartupInvestmentSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StartupInvestmentSource>
 */
class StartupInvestmentSourceFactory extends Factory
{

     protected $model = StartupInvestmentSource::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'startup_id' => \App\Models\Startup::factory(),
            'investment_source' => $this->faker->randomElement(['Business Angel', 'Public grant', 'Accelerator', 'Corporate', 'VC Fund', 'Crowd']),
        ];
    }
}
