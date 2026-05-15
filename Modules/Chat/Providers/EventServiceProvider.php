<?php

namespace Modules\Chat\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        
        \Modules\Chat\Events\MessageSent::class => [
            \Modules\Chat\Listeners\SendChatNotification::class
            
        ]  

    ];
}
