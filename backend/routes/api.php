<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::prefix('admin')->group(function () {
    // Public admin routes
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/login', [AdminAuthController::class, 'login']);
    
    // Protected admin routes
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/user', [AdminAuthController::class, 'user']);
    });
});

// Customer Routes
Route::prefix('customer')->group(function () {
    // Public customer routes
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/login', [CustomerAuthController::class, 'login']);
    
    // Protected customer routes
    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        Route::post('/logout', [CustomerAuthController::class, 'logout']);
        Route::get('/user', [CustomerAuthController::class, 'user']);
    });
});
