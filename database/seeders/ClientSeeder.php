<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory()->count(40)->active()->create();

        Client::factory()->count(7)->create(['status' => 'inactive']);

        Client::factory()->count(3)->create(['status' => 'archived']);

        $this->command->info('50 clients created successfully!');
    }
}
