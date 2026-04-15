<?php

use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:120,1')->group(function (): void {
    Route::prefix('buildings')->group(function (): void {
        Route::get('/', [BuildingController::class, 'index']);
        Route::get('/updated', [BuildingController::class, 'updated']);
        Route::get('/{building}', [BuildingController::class, 'show']);
    });

    Route::prefix('services')->group(function (): void {
        Route::get('/', [ServiceController::class, 'index']);
        Route::get('/search', [ServiceController::class, 'search']);
        Route::get('/updated', [ServiceController::class, 'updated']);
        Route::get('/{service}', [ServiceController::class, 'show']);
    });
});
