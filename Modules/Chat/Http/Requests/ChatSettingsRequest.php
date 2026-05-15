<?php

namespace Modules\Chat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'pusher_app_id' => 'required|string|max:255',
            'pusher_app_key' => 'required|string|max:255',
            'pusher_app_secret' => 'required|string|max:255',
            'pusher_app_cluster' => 'required|string|max:10',
        ];
    }

    public function messages()
    {
        return [
            'pusher_scheme.in' => 'Scheme must be either http or https.',
            'pusher_port.max' => 'Port number cannot exceed 65535.',
        ];
    }
}