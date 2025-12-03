<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes with RBAC
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Requires view-dashboard permission
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('permission:view-dashboard')->name('dashboard');

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
        // View routes - Requires view-sessions permission
        Route::middleware('permission:view-sessions')->group(function () {
            Route::get('/', [\App\Http\Controllers\SessionController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\SessionController::class, 'show'])->name('show');
        });

        // Create routes - Requires create-sessions permission
        Route::middleware('permission:create-sessions')->group(function () {
            Route::get('/create', [\App\Http\Controllers\SessionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\SessionController::class, 'store'])->name('store');
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

    // Invoices Routes - Placeholder
    Route::prefix('invoices')->name('invoices.')->middleware('permission:view-invoices')->group(function () {
        Route::get('/', function () {
            return view('placeholder', ['module' => 'Invoices']);
        })->name('index');
    });

    // Reports Routes - Placeholder
    Route::prefix('reports')->name('reports.')->middleware('permission:view-reports')->group(function () {
        Route::get('/', function () {
            return view('placeholder', ['module' => 'Reports']);
        })->name('index');
    });

    // Settings Routes - Placeholder
    Route::prefix('settings')->name('settings.')->middleware('permission:view-settings')->group(function () {
        Route::get('/', function () {
            return view('placeholder', ['module' => 'Settings']);
        })->name('index');
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
        // View routes - Requires view-clients permission
        Route::middleware('permission:view-clients')->group(function () {
            Route::get('/', [\App\Http\Controllers\ClientController::class, 'index'])->name('index');
            Route::get('/{client}', [\App\Http\Controllers\ClientController::class, 'show'])->name('show');
        });

        // Create routes - Requires create-clients permission
        Route::middleware('permission:create-clients')->group(function () {
            Route::get('/create', [\App\Http\Controllers\ClientController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\ClientController::class, 'store'])->name('store');
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

        // Export routes - Requires export-clients permission
        Route::middleware('permission:export-clients')->group(function () {
            Route::get('/export/csv', [\App\Http\Controllers\ClientController::class, 'export'])->name('export');
        });
    });
});

require __DIR__.'/auth.php';
