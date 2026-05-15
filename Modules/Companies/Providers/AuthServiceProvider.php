<?php

namespace Modules\Companies\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Posts\Models\Posts;
use Modules\Posts\Policies\Policy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Posts::class => Policy::class,
    ];
   
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(){
        

        $this->registerPolicies();


    }

}
