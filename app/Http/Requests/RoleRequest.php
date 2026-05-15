<?php

namespace App\Http\Requests;

class RoleRequest extends BasicRequest
{
    protected $storeRules = [
        'name' => 'required|string',
        'guard_name' => 'sometimes|string',
    ];

    protected $updateRules = [
        'name' => 'sometimes|string',
        'guard_name' => 'sometimes|string',
    ];
}
