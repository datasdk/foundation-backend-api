<?php

namespace App\Http\Requests;

class UserRequest extends BasicRequest
{
    protected $rules = [
        'first_name' => ['sometimes', 'nullable', 'string', 'max:255'],
        'middle_name' => ['sometimes', 'nullable', 'string', 'max:255'],
        'last_name' => ['sometimes', 'nullable', 'string', 'max:255'],
        'email' => ['sometimes', 'required', 'email', 'max:255'],
        'password' => ['sometimes', 'nullable', 'string', 'min:6', 'confirmed'],
        'type' => ['sometimes', 'nullable', 'string', 'max:255'],
        'image' => ['sometimes', 'nullable'],
        'role' => ['sometimes', 'nullable'],
        'categories' => ['sometimes', 'array'],
        'address' => ['sometimes', 'array'],
        'contact' => ['sometimes', 'array'],
        'invite' => ['sometimes', 'boolean'],
        'send_activation' => ['sometimes', 'boolean'],
        'email_verified' => ['sometimes', 'boolean'],
    ];

    protected $storeRules = [
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
    ];

    protected $updateRules = [
        'email' => ['sometimes', 'required', 'email', 'max:255'],
    ];
}
