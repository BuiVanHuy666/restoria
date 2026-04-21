<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('dang-ky', [RegisterUserController::class, 'create'])
         ->name('register');

    Route::get('/dang-nhap', [AuthenticateController::class, 'create'])
         ->name('login');

    Route::post('login', [AuthenticateController::class, 'store'])
        ->name('login.request');

    Route::get('quen-mat-khau', [PasswordResetController::class, 'create'])
        ->name('password.request');
});
