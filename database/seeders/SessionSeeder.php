<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientSession;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            $this->command->warn('No clients found. Please run ClientSeeder first.');

            return;
        }

        $notes = [
            'Initial consultation and assessment',
            'Follow-up session - discussed progress',
            'Deep dive into specific challenges',
            'Strategy planning and goal setting',
            'Monthly review and adjustment',
            'Quarterly performance review',
            'Emergency session - urgent matter',
            'Regular check-in and updates',
            'Project kickoff meeting',
            'Final review and next steps',
            null, // Some sessions without notes
        ];

        $durations = [30, 45, 60, 90, 120]; // Common session durations in minutes

        $this->command->info('Creating sample sessions...');

        // Create 100 sessions spread across clients
        for ($i = 0; $i < 100; $i++) {
            $client = $clients->random();

            // Generate random date within the last 6 months
            $daysAgo = rand(0, 180);
            $sessionDate = Carbon::now()->subDays($daysAgo);

            ClientSession::create([
                'client_id' => $client->id,
                'session_date' => $sessionDate,
                'duration_minutes' => $durations[array_rand($durations)],
                'notes' => $notes[array_rand($notes)],
            ]);
        }

        // Create some upcoming sessions (within the next 7 days)
        $this->command->info('Creating upcoming sessions...');

        for ($i = 0; $i < 10; $i++) {
            $client = $clients->random();

            // Generate date within next 7 days
            $daysAhead = rand(1, 7);
            $sessionDate = Carbon::now()->addDays($daysAhead);

            ClientSession::create([
                'client_id' => $client->id,
                'session_date' => $sessionDate,
                'duration_minutes' => $durations[array_rand($durations)],
                'notes' => 'Scheduled upcoming session',
            ]);
        }

        $this->command->info('Session seeding completed!');
        $this->command->info('Total sessions created: '.ClientSession::count());
    }
}
