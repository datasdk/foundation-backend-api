<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Permission;
use Role;
use Illuminate\Support\Facades\Log;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allPermissions = [
            'index',
            'show',
            'create',
            'update',
            'delete',
        ];

        $defaultGuard = config('auth.defaults.guard'); 

        // Opret permissions for guard 'admin'
        foreach ($allPermissions as $permissionName) {
            try {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => $defaultGuard,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to create permission {$permissionName}: " . $e->getMessage());
            }
        }

        // Roller og deres tilladelser
        $roles = [
            'admin' => ['index', 'show', 'create', 'update', 'delete'],
            'editor' => ['index', 'show', 'create', 'update', 'delete'],
            'analyzer' => ['index', 'show'],
        ];

        foreach ($roles as $roleName => $permissions) {
            try {
                // Opret eller hent rollen
                $role = Role::firstOrCreate(
                    ['name' => $roleName, 'guard_name' => $defaultGuard],
                    [
                        'description' => '',
                        'active' => true,
                    ]
                );

                // Synkroniser permissions
                $role->syncPermissions($permissions);

            } catch (\Exception $e) {
                Log::error("Failed to create or sync role {$roleName}: " . $e->getMessage());
            }
        }
    }
}
