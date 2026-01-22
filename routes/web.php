<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register',[RegisterController::class,'create'])->name('register');
    Route::post('/register',[RegisterController::class,'store']);

    Route::get('/login',[LoginController::class,'create'])->name('login');
    Route::post('/login',[LoginController::class,'store']);

    Route::get('/forgot-password',[ForgotPasswordController::class,'create'])->name('password.request');
    Route::post('/forgot-password',[ForgotPasswordController::class,'send']);

    Route::get('/reset-password',[ResetPasswordController::class,'index'])->name('password.verify');
    Route::post('/reset-password',[ResetPasswordController::class,'reset']);
});

/*
|--------------------------------------------------------------------------
| Email Verification (OTP)
|--------------------------------------------------------------------------
*/
Route::get('/verify-email',[EmailVerificationController::class,'index'])->name('verify.email');
Route::post('/verify-email',[EmailVerificationController::class,'verify']);

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout',[LoginController::class,'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard (ONLY ONE — FINAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','verified.email','log.login.activity'])
    ->get('/dashboard',[DashboardController::class,'index'])
    ->name('dashboard');
