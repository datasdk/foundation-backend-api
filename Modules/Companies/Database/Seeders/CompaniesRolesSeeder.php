<?php

namespace Modules\Companies\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Exception;

class CompaniesRolesSeeder extends Seeder
{

    public function run(): void
    {

        $guard_name = 'api';

        $roles = ['admin', 'editor', 'analyzer'];

        $locales = Config::get('app.locales', [App::getLocale()]);


        foreach ($roles as $name) {


            try {

                // Lav titler for alle sprog
                $titles = [];


                foreach ($locales as $locale) {

                    $titles[$locale] = Lang::get("teams::teams.roles.{$name}", [], $locale);

                }

                // Brug firstOrCreate i stedet for manuel kontrol
                $role = Role::firstOrCreate(
                    [
                        'name' => $name,
                        'guard_name' => $guard_name,
                    ]                   
                );

      

                // --- Permissions ---
                if (in_array($name, ['admin'])) {


                    $permission = Permission::firstOrCreate(
                        ['name' => 'recieve_email_notifications', 'guard_name' => $guard_name],
                        ['nickname' => ['en' => 'Receive email notifications']]
                    );

                       

                    if (!$role->hasPermissionTo($permission)) {

                        $role->givePermissionTo($permission);

                        $this->command->info("Permission '{$permission->name}' added to role '{$name}'.");

                    } else {

                        $this->command->info("Role '{$name}' already has permission '{$permission->name}'.");

                    }


                }


            } catch (Exception $e) {

                $this->command->error("Error adding role '{$name}': " . $e->getMessage());

            }

        }


    }

}
