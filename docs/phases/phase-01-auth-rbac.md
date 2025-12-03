# Phase 1: Authentication & RBAC Foundation

**Duration:** 2-3 days  
**Prerequisites:** Phase 0 complete  
**Complexity:** High  
**Risk Level:** Critical

---

## Overview

Implement user authentication system and establish the complete Role-Based Access Control (RBAC) foundation using spatie/laravel-permission. This phase is **critical** as all subsequent modules depend on proper permission management.

### **Objectives**

- ✅ Configure Laravel authentication (Laravel Breeze or manual)
- ✅ Create User model with HasRoles trait
- ✅ Define all permissions and roles
- ✅ Implement super-admin Gate configuration
- ✅ Create permission seeder with sample users
- ✅ Set up route middleware for RBAC
- ✅ Create basic login/register views with monochrome styling

---

## Prerequisites

- [x] Phase 0 completed
- [x] spatie/laravel-permission installed and migrated
- [x] Database connection working
- [x] Tailwind CSS configured

---

## Deliverables

1. **Authentication System**
   - Login/Register/Logout functionality
   - Password reset flow
   - Email verification (optional)

2. **RBAC Configuration**
   - 4 roles defined: Super Admin, Admin, Manager, Staff
   - 19 permissions created and assigned
   - Super-admin Gate::before configuration

3. **Seeders**
   - RolePermissionSeeder with all roles and permissions
   - Default super-admin user
   - Sample users for each role

4. **Middleware Setup**
   - Role and permission middleware registered
   - Example route protections

5. **Testing**
   - RBAC feature tests
   - Authentication tests

---

## Task Checklist

### **Task 1: Install Laravel Breeze (Recommended)**

**Option A: Using Laravel Breeze (Blade)**

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
npm install && npm run dev
```

**Option B: Manual Authentication (Skip if using Breeze)**

If not using Breeze, manually create auth controllers and views using Laravel's auth scaffolding.

**Validation:**
```bash
# Visit http://localhost:8000/register
# Should see register form
```

---

### **Task 2: Update User Model with HasRoles Trait**

**File:** `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

**Validation:**
```bash
php artisan tinker
>>> User::first() // Should work without errors
```

---

### **Task 3: Configure Super Admin Gate**

**File:** `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant Super Admin role all permissions
        // This works with @can, auth()->user()->can(), and middleware checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
```

**Validation:**
- Code compiles without errors
- Will be tested after seeder runs

---

### **Task 4: Create RolePermissionSeeder**

**File:** `database/seeders/RolePermissionSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            Permission::create(['name' => $permission]);
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
```

**Update:** `database/seeders/DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
```

**Run Seeder:**
```bash
php artisan db:seed --class=RolePermissionSeeder
```

**Validation:**
```bash
php artisan tinker
>>> \Spatie\Permission\Models\Role::count(); // Should be 4
>>> \Spatie\Permission\Models\Permission::count(); // Should be 23
>>> User::first()->roles; // Should show assigned role
```

---

### **Task 5: Register Middleware in Kernel**

Laravel 11 uses route middleware in `bootstrap/app.php`. The spatie/permissions package auto-registers middleware.

**Verify middleware registered:**

```bash
php artisan route:list
```

Middleware should include: `role`, `permission`, `role_or_permission`

---

### **Task 6: Create Test Routes with Middleware**

**File:** `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Test authentication middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('permission:view-dashboard')->name('dashboard');
    
    // Test super admin route
    Route::get('/admin-only', function () {
        return 'Super Admin Access';
    })->middleware('role:Super Admin')->name('admin.only');
    
    // Test permission route
    Route::get('/clients-test', function () {
        return 'Clients Page';
    })->middleware('permission:view-clients')->name('clients.test');
});

require __DIR__.'/auth.php';
```

**Validation:**
- Login as superadmin@example.com
- Visit `/admin-only` - should work
- Visit `/clients-test` - should work
- Login as staff@example.com
- Visit `/clients-test` - should work
- Visit `/admin-only` - should get 403 error

---

### **Task 7: Create Basic Dashboard View**

**File:** `resources/views/dashboard.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bg-secondary">
    <div class="min-h-screen">
        <!-- Simple topbar -->
        <header class="bg-bg-primary border-b border-border-light px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-text-primary">Dashboard</h1>
                <div class="flex items-center gap-4">
                    <span class="text-text-secondary">{{ auth()->user()->name }}</span>
                    <span class="text-sm text-text-tertiary">
                        Role: {{ auth()->user()->roles->pluck('name')->join(', ') }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-text-primary text-white rounded-md hover:opacity-85">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <div class="bg-bg-primary rounded-lg border border-border-light p-6">
                <h2 class="text-xl font-semibold mb-4">Welcome, {{ auth()->user()->name }}!</h2>
                
                <div class="space-y-2">
                    <p class="text-text-secondary">Your Role: <strong>{{ auth()->user()->roles->pluck('name')->join(', ') }}</strong></p>
                    
                    <h3 class="font-semibold mt-4 mb-2">Your Permissions:</h3>
                    <ul class="list-disc list-inside text-text-secondary">
                        @forelse(auth()->user()->getAllPermissions() as $permission)
                            <li>{{ $permission->name }}</li>
                        @empty
                            <li>No explicit permissions (you may have implicit Super Admin access)</li>
                        @endforelse
                    </ul>

                    <div class="mt-6">
                        <h3 class="font-semibold mb-2">Test Access:</h3>
                        <div class="flex gap-2">
                            @can('view-clients')
                                <a href="{{ route('clients.test') }}" class="px-4 py-2 bg-accent-primary text-white rounded-md">
                                    View Clients
                                </a>
                            @endcan
                            
                            @role('Super Admin')
                                <a href="{{ route('admin.only') }}" class="px-4 py-2 bg-accent-danger text-white rounded-md">
                                    Admin Only
                                </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
```

**Validation:**
- Login with different user roles
- Check permissions displayed correctly
- Buttons show/hide based on permissions

---

### **Task 8: Style Authentication Pages**

Update Breeze authentication views to match monochrome theme:

**File:** `resources/views/auth/login.blade.php` (if using Breeze)

Add custom styling to match monochrome palette. Key changes:
- Background: `bg-bg-secondary`
- Cards: `bg-bg-primary border border-border-light`
- Inputs: Tailwind custom color classes
- Buttons: `bg-text-primary text-white`

**Example snippet:**
```blade
<div class="min-h-screen bg-bg-secondary flex items-center justify-center">
    <div class="bg-bg-primary border border-border-light rounded-lg p-8 w-full max-w-md">
        <!-- Login form -->
    </div>
</div>
```

---

### **Task 9: Create RBAC Feature Tests**

**File:** `tests/Feature/RbacTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_super_admin_has_all_permissions()
    {
        $user = User::where('email', 'superadmin@example.com')->first();
        
        $this->assertTrue($user->hasRole('Super Admin'));
        $this->assertTrue($user->can('manage-roles'));
        $this->assertTrue($user->can('delete-clients'));
        $this->assertTrue($user->can('view-dashboard'));
    }

    public function test_staff_cannot_create_clients()
    {
        $user = User::where('email', 'staff@example.com')->first();
        
        $this->assertTrue($user->hasRole('Staff'));
        $this->assertFalse($user->can('create-clients'));
        $this->assertTrue($user->can('view-clients'));
    }

    public function test_manager_can_edit_but_not_delete_clients()
    {
        $user = User::where('email', 'manager@example.com')->first();
        
        $this->assertTrue($user->can('edit-clients'));
        $this->assertFalse($user->can('delete-clients'));
    }

    public function test_admin_cannot_manage_roles()
    {
        $user = User::where('email', 'admin@example.com')->first();
        
        $this->assertTrue($user->can('manage-users'));
        $this->assertFalse($user->can('manage-roles'));
    }

    public function test_staff_redirected_from_protected_route()
    {
        $user = User::where('email', 'staff@example.com')->first();
        
        $response = $this->actingAs($user)->get('/admin-only');
        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_admin_route()
    {
        $user = User::where('email', 'superadmin@example.com')->first();
        
        $response = $this->actingAs($user)->get('/admin-only');
        $response->assertStatus(200);
    }
}
```

**Run tests:**
```bash
php artisan test --filter=RbacTest
```

---

## Acceptance Criteria

Before proceeding to Phase 2, validate:

- [ ] Authentication system working (login, register, logout)
- [ ] 4 roles created: Super Admin, Admin, Manager, Staff
- [ ] 23 permissions created and assigned correctly
- [ ] Super Admin Gate::before configured in AppServiceProvider
- [ ] RolePermissionSeeder runs successfully
- [ ] 4 test users created with correct roles
- [ ] Middleware protects routes correctly (test with different users)
- [ ] Super Admin can access all routes
- [ ] Staff blocked from admin routes (403 error)
- [ ] Dashboard shows user role and permissions
- [ ] All RBAC tests pass
- [ ] Authentication pages styled with monochrome theme

---

## Testing Commands

```bash
# Seed roles and permissions
php artisan db:seed --class=RolePermissionSeeder

# Test user roles in tinker
php artisan tinker
>>> User::where('email', 'superadmin@example.com')->first()->roles
>>> User::where('email', 'staff@example.com')->first()->can('create-clients')

# Run RBAC tests
php artisan test --filter=RbacTest

# Test authentication
php artisan serve
# Login with superadmin@example.com / password
# Visit /dashboard
```

---

## Troubleshooting

### **Issue: Middleware not found**

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

### **Issue: Super Admin doesn't have permissions**

**Solution:**
- Check Gate::before in AppServiceProvider
- Clear config: `php artisan config:clear`
- Role name must be exactly 'Super Admin' (case-sensitive)

### **Issue: 403 on all routes**

**Solution:**
- Verify permissions assigned to roles
- Check route middleware syntax
- Run: `php artisan route:list` to see middleware

---

## Dependencies for Next Phase

**Phase 2 (Core Data Models) requires:**
- ✅ Authentication working
- ✅ RBAC fully configured
- ✅ Middleware protecting routes
- ✅ Test users available for development

---

## Estimated Time Breakdown

| Task | Estimated Time |
|------|----------------|
| Install Breeze | 30 minutes |
| Update User model | 15 minutes |
| Configure Super Admin Gate | 15 minutes |
| Create RolePermissionSeeder | 1 hour |
| Set up test routes | 30 minutes |
| Create dashboard view | 45 minutes |
| Style auth pages | 1 hour |
| Write RBAC tests | 1 hour |
| Testing & validation | 1 hour |
| **Total** | **~6-7 hours** |

---

## Phase Completion Sign-off

**Completed By:** [AI Agent Name]  
**Completion Date:** [YYYY-MM-DD]  
**Status:** ⬜ Not Started | ⬜ In Progress | ⬜ Complete  
**Test Results:** 
- RBAC Tests: ⬜ Pass | ⬜ Fail
- Manual Testing: ⬜ Pass | ⬜ Fail

**Notes:** 

---

**Previous Phase:** [Phase 0: Project Setup & Infrastructure](./phase-00-project-setup.md)  
**Next Phase:** [Phase 2: Core Data Models & Database](./phase-02-data-models.md)
