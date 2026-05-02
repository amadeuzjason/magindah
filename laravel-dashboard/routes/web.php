<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes (Dashboard)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/data', [DashboardController::class, 'apiData']);
Route::get('/proposal/{id}', [DashboardController::class, 'showProposal'])->name('proposal.show');
Route::get('/proposal/{id}/pdf', [PdfController::class, 'generatePdf'])->name('proposal.pdf');

// Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Magindah & Approval Actions)
Route::middleware(['custom_auth'])->group(function () {
    // Magindah Form (Renamed from Input)
    Route::get('/magindah', [InputController::class, 'show'])->name('magindah.show');
    Route::post('/api/magindah', [InputController::class, 'store'])->name('magindah.store');
    Route::post('/api/input', [InputController::class, 'store'])->name('input.store');

    // Approval Actions
    Route::get('/approvals', [DashboardController::class, 'approvals'])->name('approvals');
    Route::get('/guide', [DashboardController::class, 'guide'])->name('guide');
    Route::post('/api/approve', [DashboardController::class, 'approve']);
    Route::post('/api/reject', [DashboardController::class, 'reject']);
    Route::post('/api/update-status', [DashboardController::class, 'updateStatus']);

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Admin Proposal Edit Routes
    Route::get('/admin/proposal/{id}/edit', [\App\Http\Controllers\Admin\ProposalController::class, 'edit'])->name('admin.proposal.edit');
    Route::put('/admin/proposal/{id}', [\App\Http\Controllers\Admin\ProposalController::class, 'update'])->name('admin.proposal.update');
});
