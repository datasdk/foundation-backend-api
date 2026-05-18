<?php

namespace App\Traits\Events;

use Illuminate\Support\Facades\Event;

trait DispatchEvents
{
    /**
     * Boot the model and dispatch an event after the model is created.
     */
    protected static function boot()
    {
        // Register a "created" event listener for the model
        static::created(function ($model) {
            // Get the model's table name
            $name = $model->getTable();
            
            // Dispatch a custom event named based on the model's table name
            Event::dispatch('store.' . $name, [request(), $model]);
        });
    }
}
