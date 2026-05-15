<?php

namespace Modules\Companies\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Crm\Http\Controllers\Api\UserController;
use Modules\Companies\Http\Controllers\Api\CompaniesController;
use App\Models\User;
use Modules\Teams\Support\Pivot\TeamUser;
use Modules\Companies\Models\Companies;


class RelationServiceProvider extends ServiceProvider
{

    /**
     * Boot the application services.
     */
    public function boot(): void
    {


        // --------------------------------------
        // Whitelist includes for Orion controllers
        // --------------------------------------
        $includes = [
            // Primary includes
            "companies",
            "company",

            // Companies.*
            "companies.addresses",
            "companies.address",
            "companies.contacts",
            "companies.contact",

            // Company.*
            "company.addresses",
            "company.address",
            "company.contacts",
            "company.contact",
        ];


        UserController::whitelist($includes);
        CompaniesController::whitelist($includes);

        // --------------------------------------
        // User -> Company relations
        // --------------------------------------
        User::resolveRelationUsing('companies', function ($user) {

            return $user->hasManyThrough(
                Companies::class,   // Endelig model
                TeamUser::class,    // Pivot/through-model
                'user_id',          // teams_users.user_id → users.id
                'team_id',          // companies.team_id → teams_users.team_id
                'id',               // local key på users
                'team_id'           // local key på teams_users
            );
    
        });


        User::resolveRelationUsing('company', function ($user) {

            return $user->hasOneThrough(
                Companies::class,
                TeamUser::class,
                'user_id',     // teams_users.user_id → users.id
                'team_id',     // companies.team_id → teams_users.team_id
                'id',          // local key på users
                'team_id'      // local key på teams_users
            )->where('companies.active', true);;

        });


    }

}
