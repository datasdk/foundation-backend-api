@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Chat/Pusher Settings</h3>

    <form method="POST" action="{{ route('settings.chat.store') }}">
        @csrf

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pusher Configuration</h5>
                <small class="text-muted">Settings are stored in broadcasting.connections.pusher.*</small>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Pusher App ID</label>
                    <input type="text" class="form-control" name="pusher_app_id" 
                           value="{{ old('pusher_app_id', $chatSettings['broadcasting_connections_pusher_app_id']) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pusher App Key</label>
                    <input type="text" class="form-control" name="pusher_app_key" 
                           value="{{ old('pusher_app_key', $chatSettings['broadcasting_connections_pusher_key']) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pusher App Secret</label>
                    <input type="text" class="form-control" name="pusher_app_secret" 
                           value="{{ old('pusher_app_secret', $chatSettings['broadcasting_connections_pusher_secret']) }}" required>
                </div>


                <div class="mb-3">
                    <label class="form-label fw-bold">Pusher Cluster</label>
                    <input type="text" class="form-control" name="pusher_app_cluster" 
                           value="{{ old('pusher_app_cluster', $chatSettings['broadcasting_connections_pusher_options_cluster']) }}" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                    <a href="{{ route('settings.chat.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>

@endsection