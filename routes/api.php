<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users\ProductController;
use App\Http\Controllers\Api\Users\CategoryController;
use App\Http\Controllers\Api\Users\AuthController;
use App\Http\Controllers\Api\Users\CartController;
use App\Http\Controllers\Api\Users\AddressController;
use App\Http\Controllers\Api\Users\ProfileController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('user')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');

    Route::post('/verify-email-otp', [AuthController::class, 'verifyEmailOtp'])
        ->middleware('throttle:5,1');

    Route::post('/resend-email-otp', [AuthController::class, 'resendEmailOtp'])
        ->middleware('throttle:3,1');
});


Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('/cart/add', [CartController::class, 'add']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::put('/cart/update/{cart_item}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{cart_item}', [CartController::class, 'remove']);

    Route::get('/user/addresses', [AddressController::class, 'index']);
    Route::post('/user/addresses', [AddressController::class, 'store']);
    Route::put('/user/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/user/addresses/{id}', [AddressController::class, 'destroy']);

    Route::get('/user/profile', [ProfileController::class, 'show']);
    Route::put('/user/profile', [ProfileController::class, 'update']);

});


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category_id}', [CategoryController::class, 'show']);

Route::get('/products/top-selling', [ProductController::class, 'topSelling']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/products/id/{product_id}', [ProductController::class, 'showById']);