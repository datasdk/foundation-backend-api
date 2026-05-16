@extends('layouts.app')

@section('content')

<h1>Chat: {{ $conversation->title ?? $conversation->name }}</h1>

<p class="text-muted mb-3">{{ $conversation->description }}</p>

{{-- Chat deltagere --}}
<div class="mb-3">
    <strong>Deltagere:</strong>
    <ul>
        @foreach($conversation->participants as $participant)
            <li>{{ $participant->user->first_name ?? 'Ukjent' }} {{ $participant->user->last_name ?? '' }}</li>
        @endforeach
    </ul>
</div>

{{-- Chat messages --}}
<div class="card mb-3">
    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-striped table-bordered mb-0">
            <thead>
                <tr>
                    <th width="150">Bruger</th>
                    <th>Besked</th>
                    <th width="150">Tidspunkt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->user->first_name ?? 'Ukjent' }} {{ $message->user->last_name ?? '' }}</td>
                        <td>{{ $message->body }}</td>
                        <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center">
    {{ $messages->links() }}
</div>

{{-- Send ny besked --}}
<form method="POST" action="{{ route('chat.message.store', $conversation->id) }}">
    @csrf
    <div class="row g-2">
        <div class="col-md-10">
            <textarea class="form-control" name="body" placeholder="Skriv en besked..." rows="2" required></textarea>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Send</button>
        </div>
    </div>
</form>

@endsection
