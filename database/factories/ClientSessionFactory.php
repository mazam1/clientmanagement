<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientSession>
 */
class ClientSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'session_date' => fake()->dateTimeBetween('-6 months', '+1 month'),
            'duration_minutes' => fake()->randomElement([30, 60, 90, 120]),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
