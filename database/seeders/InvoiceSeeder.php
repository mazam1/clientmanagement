<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::has('clientSessions')->take(30)->get();

        foreach ($clients as $client) {
            $sessions = $client->clientSessions()->inRandomOrder()->take(rand(2, 4))->get();

            if ($sessions->isEmpty()) {
                continue;
            }

            $sessionIds = $sessions->pluck('id')->toArray();
            $totalMinutes = $sessions->sum('duration_minutes');
            $hourlyRate = 100;
            $subtotal = ($totalMinutes / 60) * $hourlyRate;
            $taxAmount = $subtotal * 0.1;
            $totalAmount = $subtotal + $taxAmount;

            Invoice::create([
                'client_id' => $client->id,
                'invoice_number' => 'INV-'.date('Ymd').'-'.str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT),
                'session_ids' => $sessionIds,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_status' => fake()->randomElement(['unpaid', 'partial', 'paid']),
                'payment_date' => fake()->optional(0.6)->dateTimeBetween('-2 months', 'now'),
                'issued_at' => now()->subDays(rand(1, 60)),
            ]);
        }

        $this->command->info('30 invoices created successfully!');
    }
}
