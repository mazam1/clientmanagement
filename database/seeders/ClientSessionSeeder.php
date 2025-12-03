<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientSession;
use Illuminate\Database\Seeder;

class ClientSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            $this->command->error('No clients found. Run ClientSeeder first.');

            return;
        }

        foreach ($clients as $client) {
            ClientSession::factory()
                ->count(rand(3, 5))
                ->create(['client_id' => $client->id]);
        }

        $this->command->info('200+ sessions created successfully!');
    }
}
