<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;
use Role;
use App\Http\Resources\BaseResource;
use App\Http\Resources\RoleResource;
use App\Http\Requests\RoleRequest;

class RoleController extends OrionBaseController
{
    // Den model denne controller arbejder med
    protected $model = Role::class;

    // Request klasse der validerer input
    protected $request = RoleRequest::class;


    // Relaterede ressourcer som inkluderes som default i output
    protected $includes = ['permissions'];

    // Exponerede scopes til filtrering og søgning
    protected $exposedScopes = [
        'active', 'withPermissions', 'whereId'
    ];

    // Felter der kan sorteres på
    protected $sortableBy = ['id', 'name', 'created_at'];

    // Felter der kan filtreres på
    protected $filterableBy = ['id', 'name', 'active','guard_name'];


    /**
     * Opret en ny rolle
     */
    public function store(Request $request)
    {

        $validated = $request->validated();

        $role = Role::create($validated);

        return response()->json(new $this->resource($role), 201);

    }


    /**
     * Opdater en eksisterende rolle
     */
    public function update(Request $request, ...$args)
    {
        $validated = $request->validated();

        $id = $args[0];

        $role = Role::findOrFail($id);
        $role->update($validated);

        return response()->json(new $this->resource($role), 200);
    }

    /**
     * Slet en rolle
     */
    public function destroy(Request $request, ...$args)
    {
        
        $id = $args[0];

        $role = Role::findOrFail($id);

        $role->delete();

        return response()->noContent(); // No Content
    }

    /**
     * Hent roller med en bestemt permission
     */
    public function belongsTo($permission)
    {
        $roles = Role::whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->get();

        return BaseResource::collection($roles);
    }
}
