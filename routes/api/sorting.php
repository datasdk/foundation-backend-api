<?php

use App\Http\Controllers\Api\SortingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('sorting')->group(function () {
    Route::post('change', [SortingController::class, 'changePosition'])->name('sorting.change');
});
