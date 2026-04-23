<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('dang-ky', [RegisterUserController::class, 'create'])
         ->name('register');

    Route::post('register', [RegisterUserController::class, 'store'])
         ->name('register.store');

    Route::get('dang-nhap', [AuthenticateController::class, 'create'])
         ->name('login');

    Route::post('login', [AuthenticateController::class, 'store'])
         ->name('login.request');

    Route::get('quen-mat-khau', [PasswordResetController::class, 'create'])
         ->name('password.request');

    Route::post('forgot-password', [PasswordResetController::class, 'store'])
         ->name('password-reset.store');

    Route::get('dat-lai-mat-khau/{token}', [NewPasswordController::class, 'create'])
         ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
         ->name('reset-password.store');

    Route::get('auth/{provider}', [SocialAuthController::class, 'redirect'])
        ->name('social.login');

    Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->name('social.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('xac-nhan-email', [VerifyEmailController::class, 'notice'])
         ->name('verification.notice');

    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
         ->middleware(['signed', 'throttle:6,1'])
         ->name('verification.verify');

    Route::post('email/resend-verify-link', [VerifyEmailController::class, 'resend'])
         ->name('verification.resend');

    Route::delete('logout', [AuthenticateController::class, 'destroy'])->name('logout');
});
