<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    if (config('available.install_route_enabled')) {
        Route::get('/module/install', function () {
            abort_unless(auth()->check(), 403);

            return response()->json([
                'status' => 'install route active',
            ]);
        });
    }
});
