<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * This property holds an array of event listener mappings that define
     * which listeners should be executed for each specific event.
     *
     * @var array
     */
    protected $listen = [
        // Listen to the Logout event and handle it using the LogoutListener
        \Illuminate\Auth\Events\Logout::class => [
       
        ],
        
        // Listen to the Login event and handle it using the LoginListener
        \Illuminate\Auth\Events\Login::class => [
      
        ],

        \Modules\Crm\Events\Auth\UserAfterAuthenticated::class => [
            \Modules\Crm\Listeners\Auth\SetUserHeaders::class
        ],

        // ActionModel Creating Event
        \App\Events\ActionsModels\ModelCreating::class => [
 
        ],

        // ActionModel Created Event
        \App\Events\ActionsModels\ModelCreated::class => [
            \DataSDK\Addresses\Listeners\ActionsModels\Addresses\HandleAddressesCreated::class,
            \DataSDK\Addresses\Listeners\ActionsModels\Contacts\HandleContactsCreated::class,
        ],

        // ActionModel Updated Event
        \App\Events\ActionsModels\ModelUpdated::class => [
            \DataSDK\Addresses\Listeners\ActionsModels\Addresses\HandleAddressesUpdated::class,
            \DataSDK\Addresses\Listeners\ActionsModels\Contacts\HandleContactsUpdated::class,
        ],
    ];

    /**
     * Bootstrap any application services.
     *
     * This method is used to register any events and listeners.
     * It calls the parent boot method to ensure the event listeners are properly registered.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
