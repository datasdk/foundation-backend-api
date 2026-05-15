@extends('layouts.app')

@section('actions')
    <a href="{{ route('chat.create') }}" class="btn btn-primary">Opret chat</a>
@endsection

@section('content')
    <livewire:table :config="Modules\Chat\Tables\ChatTable::class" />
@endsection
