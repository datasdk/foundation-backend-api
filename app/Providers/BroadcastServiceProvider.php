<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * This method is used to bootstrap any broadcasting services required by the application.
     * It sets up routes for broadcasting and loads channel definitions.
     *
     * @return void
     */
    public function boot()
    {
        // Register the broadcasting routes
        Broadcast::routes();

        // Include the channel definitions from the routes file
        require base_path('routes/channels.php');
    }
}
