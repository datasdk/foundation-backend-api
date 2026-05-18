<?php

namespace App\Traits\Roles;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

trait Roles
{
    /**
     * Get the permission name for the model.
     *
     * @return string
     */
    public function permissionName()
    {
        return strtolower($this->getTable() . '.*.' . $this->id);
    }

    /**
     * Accessor for the permission name attribute.
     *
     * @return string
     */
    public function getPermissionNameAttribute()
    {
        return $this->permissionName();
    }

    /**
     * Attach the model's permission to roles.
     *
     * @param array|int $roles
     * @param bool $sync
     * @return $this
     */
    public function attachToRoles($roles, bool $sync = true)
    {
        if ($permission = $this->createPermission()) {
            if ($sync) {
                foreach (Role::all() as $role) {
                    $role->revokePermissionTo($permission->id);
                }
            }

            foreach (Role::findMany((array) $roles) as $role) {
                $role->givePermissionTo($permission->id);
            }
        }

        return $this;
    }

    /**
     * Create the permission for the model.
     *
     * @return \Spatie\Permission\Models\Permission
     */
    public function createPermission()
    {
        $defaultGuard = config('auth.defaults.guard');

        $permissionName = $this->permissionName();
        return Permission::firstOrCreate([
            'name' => $permissionName,
            'guard_name' => $defaultGuard
        ]);
    }
}
