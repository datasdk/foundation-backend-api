<?php

namespace Modules\Chat\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Chat\Http\Requests\ChatSettingsRequest;
use App\Http\Controllers\Controller;
use Settings;

class ChatSettingsController extends Controller
{
    public function index()
    {
        $chatSettings = [
            'broadcasting_connections_pusher_key' => config('broadcasting.connections.pusher.key', ''),
            'broadcasting_connections_pusher_secret' => config('broadcasting.connections.pusher.secret', ''),
            'broadcasting_connections_pusher_app_id' => config('broadcasting.connections.pusher.app_id', ''),
            'broadcasting_connections_pusher_options_cluster' => config('broadcasting.connections.pusher.options.cluster', 'mt1')
        ];

        return view('chat::settings.index', compact('chatSettings'));
    }

    public function store(ChatSettingsRequest $request)
    {
        $settings = [
            'key' => $request->input('pusher_app_key'),
            'secret' => $request->input('pusher_app_secret'),
            'app_id' => $request->input('pusher_app_id'),
            'options' => [
                'cluster' => $request->input('pusher_app_cluster')
            ],
        ];

        Settings::set('broadcasting.connections.pusher', $settings);

        return redirect()->route('settings.chat.index')->with('success', 'Chat settings updated successfully.');
    }
}