<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:3,1');
  
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])
        ->middleware('throttle:3,5');

    Route::get('/reset-password', [ResetPasswordController::class, 'index'])->name('password.verify');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->middleware('throttle:5,5');
});

// Email verification (accessible to authenticated users)
Route::get('/verify-email', [EmailVerificationController::class, 'index'])->name('verify.email');
Route::post('/verify-email', [EmailVerificationController::class, 'verify'])
    ->middleware('throttle:5,5');

// Logout (accessible to all authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Main dashboard - redirects to role-specific dashboard
Route::middleware(['auth', 'verified.email', 'log.login.activity'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// ============================================
// ADMIN ROUTES - Prefix: /admin
// ============================================
Route::middleware(['auth', 'verified.email', 'log.login.activity', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        // Admin Supplier Management
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers/create', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
    });

// ============================================
// USER ROUTES - Prefix: /users
// ============================================
Route::middleware(['auth', 'verified.email', 'log.login.activity', 'role:user'])
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        // User Dashboard
        Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
        
    });