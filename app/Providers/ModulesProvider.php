<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Factories\Factory;
use Module;


class ModulesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * This method is used to register any services needed by the application.
     * It's currently empty as no specific services need to be registered here.
     *
     * @return void
     */
    public function register()
    {
       

        Factory::guessFactoryNamesUsing(function (string $modelName) {
         
            if (str_starts_with($modelName, 'Modules\\')) {
                // Fjerner "Modules\" fra starten
                $withoutModules = str_replace("Modules\\", "", $modelName);
                
                // Splitter for at finde modulnavnet
                $parts = explode("\\", $withoutModules);
                $moduleName = $parts[0];
     
                return "Modules\\$moduleName\\Database\\factories\\" . class_basename($modelName) . "Factory";
            }
        
            // Hvis modellen ikke starter med "Modules\", brug standardsti
            return "Database\\Factories\\" . class_basename($modelName) . "Factory";
        });
        
        

    }

    /**
     * Bootstrap services.
     *
     * This method is used to bootstrap any services or functionality
     * that the application requires once all services are registered.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
