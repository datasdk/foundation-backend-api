<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;
use Exception;

class ModuleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Seeding all active modules...");

        // Hent alle aktive moduler
        $modules = Module::allEnabled(); // kun enabled modules
        // eller Module::all() hvis du vil have alle

        foreach ($modules as $module) {
            $moduleName = $module->getName();

            $this->command->info("Seeding module: {$moduleName}");

            try {
                Artisan::call("module:seed", [
                    'module' => $moduleName
                ]);

                $this->command->info(Artisan::output());

            } catch (Exception $e) {
                $this->command->error("Fejl ved module: {$moduleName} - " . $e->getMessage());
            }
        }

        $this->command->info("Alle moduler seedet.");
    }
}
