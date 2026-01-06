<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\WishlistController;

Route::post('/register', [Authcontroller::class, 'register']);
Route::post('/login', [Authcontroller::class, 'login']);
// Route::get('/email/verify/{id}/{hash}', [Authcontroller::class, 'verifyEmail'])
//     ->name('verification.verify');
// Route::post('/email/resend', [Authcontroller::class, 'resendEmailVerification'])
//     ->name('verification.send');
Route::post('/forget-password', [Authcontroller::class, 'ForgetPassword']);
Route::post('/reset-password', [Authcontroller::class, 'ResetPassword']);


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [Authcontroller::class, 'logout']);
    Route::get('/me', [Authcontroller::class, 'me']);
});

Route::post('/food', [FoodController::class, 'store']);
Route::get('/food', [FoodController::class, 'index']);
Route::get('/food/{id}', [FoodController::class, 'show']);
Route::delete('/food/{id}', [FoodController::class, 'destroy']);
Route::post('/food/{id}', [FoodController::class, 'update']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/wishlist', [WishlistController::class, 'add']);
    Route::delete('/wishlist', [WishlistController::class, 'remove']);
    Route::get('/wishlist', [WishlistController::class, 'list']);
});




Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {});
