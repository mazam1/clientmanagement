<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 5000);
        $taxAmount = $subtotal * 0.1;
        $totalAmount = $subtotal + $taxAmount;

        return [
            'client_id' => Client::factory(),
            'invoice_number' => 'INV-'.fake()->unique()->numerify('######'),
            'session_ids' => [],
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_status' => fake()->randomElement(['unpaid', 'partial', 'paid']),
            'payment_date' => fake()->optional(0.5)->dateTimeBetween('-3 months', 'now'),
            'issued_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
