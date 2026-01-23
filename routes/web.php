<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

Route::get('/verify-email', [EmailVerificationController::class, 'index'])->name('verify.email');
Route::post('/verify-email', [EmailVerificationController::class, 'verify'])
    ->middleware('throttle:5,5');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'verified.email', 'log.login.activity'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');


Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
Route::post('/suppliers/create', [SupplierController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');