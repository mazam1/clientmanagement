<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes with RBAC
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Requires view-dashboard permission
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
        ->middleware('permission:view-dashboard')
        ->name('dashboard');

    // Global search route
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');

    // Test super admin route
    Route::get('/admin-only', function () {
        return 'Super Admin Access - You have full permissions!';
    })->middleware('role:Super Admin')->name('admin.only');

    // Test permission route
    Route::get('/clients-test', function () {
        return 'Clients Page - You have view-clients permission!';
    })->middleware('permission:view-clients')->name('clients.test');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Session Management Routes - Protected by RBAC
    Route::prefix('sessions')->name('sessions.')->group(function () {
        // Create routes - Requires create-sessions permission (must be before {id} routes)
        Route::middleware('permission:create-sessions')->group(function () {
            Route::get('/create', [\App\Http\Controllers\SessionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\SessionController::class, 'store'])->name('store');
        });

        // View routes - Requires view-sessions permission
        Route::middleware('permission:view-sessions')->group(function () {
            Route::get('/', [\App\Http\Controllers\SessionController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\SessionController::class, 'show'])->name('show');
        });

        // Edit routes - Requires edit-sessions permission
        Route::middleware('permission:edit-sessions')->group(function () {
            Route::get('/{id}/edit', [\App\Http\Controllers\SessionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\SessionController::class, 'update'])->name('update');
        });

        // Delete routes - Requires delete-sessions permission
        Route::middleware('permission:delete-sessions')->group(function () {
            Route::delete('/{id}', [\App\Http\Controllers\SessionController::class, 'destroy'])->name('destroy');
        });
    });

    // Invoice Management Routes - Protected by RBAC
    Route::prefix('invoices')->name('invoices.')->group(function () {
        // Create routes - Requires create-invoices permission (must be before {id} routes)
        Route::middleware('permission:create-invoices')->group(function () {
            Route::get('/create', [\App\Http\Controllers\InvoiceController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\InvoiceController::class, 'store'])->name('store');
        });

        // Export route - Requires export-invoices permission (must be before {id} routes)
        Route::middleware('permission:export-invoices')->group(function () {
            Route::get('/export', [\App\Http\Controllers\InvoiceController::class, 'export'])->name('export');
        });

        // Print route - Requires view-invoices permission (must be before {id} routes)
        Route::middleware('permission:view-invoices')->group(function () {
            Route::get('/{id}/print', [\App\Http\Controllers\InvoiceController::class, 'print'])->name('print');
        });

        // View routes - Requires view-invoices permission
        Route::middleware('permission:view-invoices')->group(function () {
            Route::get('/', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('show');
        });

        // Edit routes - Requires edit-invoices permission
        Route::middleware('permission:edit-invoices')->group(function () {
            Route::get('/{id}/edit', [\App\Http\Controllers\InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\InvoiceController::class, 'update'])->name('update');
        });

        // Delete routes - Requires delete-invoices permission
        Route::middleware('permission:delete-invoices')->group(function () {
            Route::delete('/{id}', [\App\Http\Controllers\InvoiceController::class, 'destroy'])->name('destroy');
        });

        // Payment status routes - Requires edit-invoices permission
        Route::middleware('permission:edit-invoices')->group(function () {
            Route::post('/{id}/mark-paid', [\App\Http\Controllers\InvoiceController::class, 'markAsPaid'])->name('mark-paid');
            Route::post('/{id}/mark-unpaid', [\App\Http\Controllers\InvoiceController::class, 'markAsUnpaid'])->name('mark-unpaid');
        });
    });

    // Reports Routes - Placeholder
    Route::prefix('reports')->name('reports.')->middleware('permission:view-reports')->group(function () {
        Route::get('/', function () {
            return view('placeholder', ['module' => 'Reports']);
        })->name('index');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->middleware('permission:view-settings')->group(function () {
        Route::get('/', [\App\Http\Controllers\SettingController::class, 'index'])->name('index');
        Route::put('/', [\App\Http\Controllers\SettingController::class, 'update'])
            ->middleware('permission:edit-settings')
            ->name('update');
    });

    // User Management Routes - Placeholder
    Route::prefix('users')->name('users.')->middleware('permission:manage-users')->group(function () {
        Route::get('/', function () {
            return view('placeholder', ['module' => 'User Management']);
        })->name('index');
    });

    // Roles Management Routes - Placeholder
    Route::prefix('roles')->name('roles.')->middleware('role:Super Admin')->group(function () {
        Route::get('/', function () {
            return view('placeholder', ['module' => 'Roles & Permissions']);
        })->name('index');
    });

    // Client Management Routes - Protected by RBAC
    Route::prefix('clients')->name('clients.')->group(function () {
        // Create routes - Requires create-clients permission (must be before {client} routes)
        Route::middleware('permission:create-clients')->group(function () {
            Route::get('/create', [\App\Http\Controllers\ClientController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\ClientController::class, 'store'])->name('store');
        });

        // Export routes - Requires export-clients permission (must be before {client} routes)
        Route::middleware('permission:export-clients')->group(function () {
            Route::get('/export/csv', [\App\Http\Controllers\ClientController::class, 'export'])->name('export');
        });

        // View routes - Requires view-clients permission
        Route::middleware('permission:view-clients')->group(function () {
            Route::get('/', [\App\Http\Controllers\ClientController::class, 'index'])->name('index');
            Route::get('/{client}', [\App\Http\Controllers\ClientController::class, 'show'])->name('show');
        });

        // Edit routes - Requires edit-clients permission
        Route::middleware('permission:edit-clients')->group(function () {
            Route::get('/{client}/edit', [\App\Http\Controllers\ClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [\App\Http\Controllers\ClientController::class, 'update'])->name('update');
        });

        // Delete routes - Requires delete-clients permission
        Route::middleware('permission:delete-clients')->group(function () {
            Route::delete('/{client}', [\App\Http\Controllers\ClientController::class, 'destroy'])->name('destroy');
        });
    });
});

require __DIR__.'/auth.php';
