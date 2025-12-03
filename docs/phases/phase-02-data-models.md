# Phase 2: Core Data Models & Database

**Duration:** 2-3 days  
**Prerequisites:** Phase 0, Phase 1 complete  
**Complexity:** Medium  
**Risk Level:** Medium

---

## Overview

Create all core database models, migrations, relationships, and seeders for Clients, Sessions, and Invoices. This establishes the data foundation for the entire application.

### **Objectives**

- ✅ Create migrations for clients, sessions, invoices tables
- ✅ Build Eloquent models with relationships
- ✅ Add accessors, mutators, and scopes
- ✅ Create model factories for testing
- ✅ Seed database with realistic sample data
- ✅ Verify data integrity and relationships

---

## Prerequisites

- [x] Phase 0 and 1 completed
- [x] Database connection working
- [x] RBAC tables migrated

---

## Deliverables

1. **Migrations**
   - create_clients_table
   - create_sessions_table
   - create_invoices_table

2. **Models**
   - Client model with relationships
   - Session model with relationships
   - Invoice model with relationships

3. **Factories**
   - ClientFactory
   - SessionFactory
   - InvoiceFactory

4. **Seeders**
   - ClientSeeder (50 clients)
   - SessionSeeder (200 sessions)
   - InvoiceSeeder (30 invoices)

5. **Testing**
   - Model relationship tests
   - Database integrity tests

---

## Task Checklist

### **Task 1: Create Client Migration**

```bash
php artisan make:migration create_clients_table
```

**File:** `database/migrations/YYYY_MM_DD_XXXXXX_create_clients_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
```

---

### **Task 2: Create Session Migration**

```bash
php artisan make:migration create_sessions_table
```

**File:** `database/migrations/YYYY_MM_DD_XXXXXX_create_sessions_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->dateTime('session_date')->index();
            $table->integer('duration_minutes');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
```

---

### **Task 3: Create Invoice Migration**

```bash
php artisan make:migration create_invoices_table
```

**File:** `database/migrations/YYYY_MM_DD_XXXXXX_create_invoices_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique()->index();
            $table->json('session_ids')->nullable(); // Array of session IDs
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->index();
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
```

**Run migrations:**
```bash
php artisan migrate
```

---

### **Task 4: Create Client Model**

```bash
php artisan make:model Client
```

**File:** `app/Models/Client.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get all sessions for the client.
     */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Get all invoices for the client.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Scope to filter active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter inactive clients.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to filter archived clients.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Get total billable hours for client.
     */
    public function getTotalBillableHoursAttribute()
    {
        return $this->sessions->sum('duration_minutes') / 60;
    }

    /**
     * Get total revenue from paid invoices.
     */
    public function getTotalRevenueAttribute()
    {
        return $this->invoices()->where('payment_status', 'paid')->sum('total_amount');
    }
}
```

---

### **Task 5: Create Session Model**

```bash
php artisan make:model Session
```

**File:** `app/Models/Session.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'session_date',
        'duration_minutes',
        'notes',
    ];

    protected $casts = [
        'session_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the client that owns the session.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope to get upcoming sessions.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('session_date', '>', now());
    }

    /**
     * Scope to get past sessions.
     */
    public function scopePast($query)
    {
        return $query->where('session_date', '<=', now());
    }

    /**
     * Get duration in hours.
     */
    public function getDurationHoursAttribute()
    {
        return number_format($this->duration_minutes / 60, 2);
    }
}
```

---

### **Task 6: Create Invoice Model**

```bash
php artisan make:model Invoice
```

**File:** `app/Models/Invoice.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_number',
        'session_ids',
        'subtotal',
        'tax_amount',
        'total_amount',
        'payment_status',
        'payment_date',
        'issued_at',
    ];

    protected $casts = [
        'session_ids' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'issued_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the client that owns the invoice.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get sessions included in this invoice.
     */
    public function sessions()
    {
        if (empty($this->session_ids)) {
            return collect();
        }
        
        return Session::whereIn('id', $this->session_ids)->get();
    }

    /**
     * Scope to filter unpaid invoices.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    /**
     * Scope to filter paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope to filter partial invoices.
     */
    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);
    }
}
```

---

### **Task 7: Create Factories**

**Client Factory:**
```bash
php artisan make:factory ClientFactory
```

**File:** `database/factories/ClientFactory.php`

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['active', 'inactive', 'archived']),
        ];
    }

    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}
```

**Session Factory:**
```bash
php artisan make:factory SessionFactory
```

**File:** `database/factories/SessionFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
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
```

**Invoice Factory:**
```bash
php artisan make:factory InvoiceFactory
```

**File:** `database/factories/InvoiceFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 5000);
        $taxAmount = $subtotal * 0.1; // 10% tax
        $totalAmount = $subtotal + $taxAmount;

        return [
            'client_id' => Client::factory(),
            'invoice_number' => 'INV-' . fake()->unique()->numerify('######'),
            'session_ids' => [], // Will be populated by seeder
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_status' => fake()->randomElement(['unpaid', 'partial', 'paid']),
            'payment_date' => fake()->optional(0.5)->dateTimeBetween('-3 months', 'now'),
            'issued_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
```

---

### **Task 8: Create Seeders**

**Client Seeder:**
```bash
php artisan make:seeder ClientSeeder
```

**File:** `database/seeders/ClientSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Create 40 active clients
        Client::factory()->count(40)->active()->create();
        
        // Create 7 inactive clients
        Client::factory()->count(7)->create(['status' => 'inactive']);
        
        // Create 3 archived clients
        Client::factory()->count(3)->create(['status' => 'archived']);

        $this->command->info('50 clients created successfully!');
    }
}
```

**Session Seeder:**
```bash
php artisan make:seeder SessionSeeder
```

**File:** `database/seeders/SessionSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Session;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            $this->command->error('No clients found. Run ClientSeeder first.');
            return;
        }

        // Create 3-5 sessions per client (total ~200)
        foreach ($clients as $client) {
            Session::factory()
                ->count(rand(3, 5))
                ->create(['client_id' => $client->id]);
        }

        $this->command->info('200+ sessions created successfully!');
    }
}
```

**Invoice Seeder:**
```bash
php artisan make:seeder InvoiceSeeder
```

**File:** `database/seeders/InvoiceSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Session;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::has('sessions')->take(30)->get();

        foreach ($clients as $client) {
            // Get 2-4 random sessions for this client
            $sessions = $client->sessions()->inRandomOrder()->take(rand(2, 4))->get();
            
            if ($sessions->isEmpty()) {
                continue;
            }

            $sessionIds = $sessions->pluck('id')->toArray();
            $totalMinutes = $sessions->sum('duration_minutes');
            $hourlyRate = 100; // $100/hour
            $subtotal = ($totalMinutes / 60) * $hourlyRate;
            $taxAmount = $subtotal * 0.1;
            $totalAmount = $subtotal + $taxAmount;

            Invoice::create([
                'client_id' => $client->id,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT),
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
```

**Update DatabaseSeeder:**

**File:** `database/seeders/DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            ClientSeeder::class,
            SessionSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
```

**Run all seeders:**
```bash
php artisan db:seed
```

---

## Acceptance Criteria

- [ ] All 3 migrations created and run successfully
- [ ] Client, Session, Invoice models created with relationships
- [ ] All model relationships work correctly (test in tinker)
- [ ] Factories create valid test data
- [ ] Seeders populate database:
  - 50 clients (40 active, 7 inactive, 3 archived)
  - 200+ sessions distributed across clients
  - 30 invoices with associated sessions
- [ ] No database integrity errors
- [ ] Soft deletes work on Client model
- [ ] Accessors and scopes function correctly

---

## Testing Commands

```bash
# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Test relationships in tinker
php artisan tinker
>>> $client = \App\Models\Client::first();
>>> $client->sessions; // Should show sessions
>>> $client->invoices; // Should show invoices
>>> $session = \App\Models\Session::first();
>>> $session->client; // Should show client
>>> $invoice = \App\Models\Invoice::first();
>>> $invoice->client; // Should show client
>>> $invoice->sessions(); // Should show sessions

# Test scopes
>>> \App\Models\Client::active()->count(); // Should be 40
>>> \App\Models\Invoice::unpaid()->count(); // Should show unpaid count

# Test accessors
>>> $client->total_billable_hours;
>>> $client->total_revenue;
```

---

## Phase Completion Sign-off

**Completed By:** [AI Agent Name]  
**Completion Date:** [YYYY-MM-DD]  
**Status:** ⬜ Not Started | ⬜ In Progress | ⬜ Complete  

---

**Next Phase:** [Phase 3: Admin Layout & UI Foundation](./phase-03-admin-layout.md)
