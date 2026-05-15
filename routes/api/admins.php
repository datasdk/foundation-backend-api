<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admins')->name('api.admins.')->middleware('auth:api')->group(function () {
    Route::post('invite', [AdminController::class, 'invite'])->name('invite');
    Route::delete('{admin}', [AdminController::class, 'destroy'])->name('destroy');
});
