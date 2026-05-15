<?php

namespace App\Http\Requests;

class CategoryRequest extends BasicRequest
{
    protected $storeRules = [
        'name' => 'required',
        'type' => 'required|string',
        'description' => 'sometimes|nullable',
        'parent_id' => 'sometimes|nullable|integer',
        'tags' => 'sometimes|array',
    ];

    protected $updateRules = [
        'name' => 'sometimes',
        'type' => 'sometimes|string',
        'description' => 'sometimes|nullable',
        'parent_id' => 'sometimes|nullable|integer',
        'tags' => 'sometimes|array',
    ];
}
