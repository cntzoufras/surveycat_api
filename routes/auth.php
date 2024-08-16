<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
     ->middleware('guest')
     ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
     ->middleware('guest')
     ->name('password.store');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
     ->middleware(['signed', 'throttle:6,1'])
     ->name('verification.verify');

// User requests resend verification link
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
     ->middleware(['auth:sanctum', 'throttle:6,1'])
     ->name('verification.send');

//Route::middleware('auth:sanctum')->group(function () {
//    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
////         ->middleware('auth:sanctum', 'throttle:6,1')
////         ->name('logout');
//});

