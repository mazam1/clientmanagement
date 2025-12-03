<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles, permissions, and test users
        $this->call([
            RolePermissionSeeder::class,
            ClientSeeder::class,
            ClientSessionSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
