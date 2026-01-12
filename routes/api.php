<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\SessionController;
use Illuminate\Support\Facades\Route;

// API Version 1
Route::prefix('v1')->group(function () {
    // Client endpoints
    Route::apiResource('clients', ClientController::class);
    Route::get('clients-stats', [ClientController::class, 'stats']);

    // Session endpoints
    Route::apiResource('sessions', SessionController::class);
    Route::get('sessions-stats', [SessionController::class, 'stats']);
    Route::get('sessions-upcoming', [SessionController::class, 'upcoming']);
});
