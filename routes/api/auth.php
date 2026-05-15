<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;


Route::prefix('auth')->middleware('auth.both:api')->group(function () {


    Route::post('login', [AuthController::class, 'login'])->name('api.user.login');

    Route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])->name('api.user.logoff');

    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('api.user.refresh-token');

    Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('api.password.request');

    Route::post('resend-activation-email', [AuthController::class, 'resendActivationEmail'])->name('api.resend-activation-email');

    Route::post('resend-invitation', [AuthController::class, 'resendInvitation'])->name('api.resend-invitation');

});
