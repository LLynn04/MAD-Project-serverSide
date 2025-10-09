<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;

Route::post('/register', [Authcontroller::class, 'register']);
Route::post('/login', [Authcontroller::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [Authcontroller::class, 'verifyEmail'])
    ->name('verification.verify');
Route::post('/email/resend', [Authcontroller::class, 'resendEmailVerification'])
    ->name('verification.send');
Route::post('/forget-password', [Authcontroller::class, 'ForgetPassword']);
Route::post('/reset-password', [Authcontroller::class, 'ResetPassword']);


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [Authcontroller::class, 'logout']);
    Route::get('/me', [Authcontroller::class, 'me']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {});
