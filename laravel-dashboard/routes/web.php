<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['custom_auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/approvals', [DashboardController::class, 'approvals'])->name('approvals');
    Route::post('/api/approve', [DashboardController::class, 'approve']);
    Route::post('/api/reject', [DashboardController::class, 'reject']);
    Route::post('/api/update-status', [DashboardController::class, 'updateStatus']); // New generic status update
    Route::get('/api/data', [DashboardController::class, 'apiData']);

    // Input Form Routes
    Route::get('/input', [InputController::class, 'show'])->name('input.show');
    Route::post('/api/input', [InputController::class, 'store'])->name('input.store');
});
