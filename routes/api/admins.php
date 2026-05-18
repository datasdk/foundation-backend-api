<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('admins')->name('api.admins.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('roles', [AdminController::class, 'roles'])->name('roles');
    Route::post('invite', [AdminController::class, 'invite'])->name('invite');
    Route::patch('{admin}', [AdminController::class, 'update'])->name('update');
    Route::delete('{admin}', [AdminController::class, 'destroy'])->name('destroy');
});
