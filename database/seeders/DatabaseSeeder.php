<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;

use DataSDK\Addresses\Database\Seeders\CountrySeeder;
use Modules\Email\Database\Seeders\EmailTemplateSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call([
            RoleSeeder::class,
            CountrySeeder::class,
            EmailTemplateSeeder::class,
         
            ModuleSeeder::class,    
        ]);
 
    }

}
