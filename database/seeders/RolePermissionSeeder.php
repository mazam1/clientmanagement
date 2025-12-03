<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'view-dashboard',

            // Clients
            'view-clients',
            'create-clients',
            'edit-clients',
            'delete-clients',
            'export-clients',

            // Sessions
            'view-sessions',
            'create-sessions',
            'edit-sessions',
            'delete-sessions',

            // Invoices
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            'export-invoices',

            // Reports
            'view-reports',

            // Settings
            'view-settings',
            'edit-settings',

            // User Management
            'manage-users',

            // Role Management
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - Gets all permissions via Gate::before
        $superAdmin = Role::create(['name' => 'Super Admin']);
        // Optionally assign all permissions explicitly (not required due to Gate::before)
        // $superAdmin->givePermissionTo(Permission::all());

        // Admin - All permissions except manage-roles
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'view-dashboard',
            'view-clients', 'create-clients', 'edit-clients', 'delete-clients', 'export-clients',
            'view-sessions', 'create-sessions', 'edit-sessions', 'delete-sessions',
            'view-invoices', 'create-invoices', 'edit-invoices', 'delete-invoices', 'export-invoices',
            'view-reports',
            'view-settings', 'edit-settings',
            'manage-users',
        ]);

        // Manager - View/Edit clients, sessions, invoices (no delete)
        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view-dashboard',
            'view-clients', 'create-clients', 'edit-clients',
            'view-sessions', 'create-sessions', 'edit-sessions',
            'view-invoices', 'create-invoices', 'edit-invoices', 'export-invoices',
            'view-reports',
        ]);

        // Staff - View only
        $staff = Role::create(['name' => 'Staff']);
        $staff->givePermissionTo([
            'view-dashboard',
            'view-clients',
            'view-sessions',
        ]);

        // Create default users

        // Super Admin User
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superAdminUser->assignRole('Super Admin');

        // Admin User
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('Admin');

        // Manager User
        $managerUser = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $managerUser->assignRole('Manager');

        // Staff User
        $staffUser = User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $staffUser->assignRole('Staff');

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Default users created:');
        $this->command->info('- superadmin@example.com (Super Admin) - password: password');
        $this->command->info('- admin@example.com (Admin) - password: password');
        $this->command->info('- manager@example.com (Manager) - password: password');
        $this->command->info('- staff@example.com (Staff) - password: password');
    }
}
