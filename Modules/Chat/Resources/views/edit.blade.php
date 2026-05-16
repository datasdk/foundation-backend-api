@extends('layouts.app')

@section('content')

<h1>Rediger chat: {{ $conversation->title ?? $conversation->name }}</h1>

<form method="POST" action="{{ route('chat.update', $conversation->id) }}">
    @csrf
    @method('PATCH')

    <table class="table table-bordered">
        <tbody>
            <!-- Titel -->
            <tr>
                <td width="150">Titel</td>
                <td>
                    <input type="text" class="form-control" name="title" value="{{ old('title', $conversation->title) }}" required>
                </td>
            </tr>

            <!-- Beskrivelse -->
            <tr>
                <td>Beskrivelse</td>
                <td>
                    <textarea class="form-control" name="description" rows="3">{{ old('description', $conversation->description) }}</textarea>
                </td>
            </tr>

            <!-- Direkte besked -->
            <tr>
                <td>Direkte besked</td>
                <td>
                    <input type="checkbox" class="form-check-input" name="direct_message" value="1" {{ $conversation->direct_message ? 'checked' : '' }}>
                </td>
            </tr>

            <!-- Chat medlemmer -->
            <tr>
                <td>Chat deltagere</td>
                <td>
                    <div id="participants" x-data="chatParticipants()">
                        <template x-for="(participant, index) in participants" :key="index">
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <livewire:select-user 
                                        :key="index" 
                                        :value="participant.user_id" 
                                        name="participants[index][user_id]" 
                                        wire:model.defer="participants[index].user_id"
                                    />
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-danger btn-sm" @click="remove(index)">Fjern</button>
                                </div>
                            </div>
                        </template>

                        <button type="button" class="btn btn-secondary btn-sm mt-2" @click="add()">Tilføj deltager</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Opdater chat</button>
        <a href="{{ route('chat.index') }}" class="btn btn-secondary">Tilbage</a>
    </div>
</form>

@endsection

@push('scripts')
<script>
function chatParticipants() {
    return {
        participants: [
            @foreach($conversation->participants as $p)
                { user_id: {{ $p->user_id ?? 'null' }} },
            @endforeach
        ],

        add() {
            this.participants.push({user_id: null});
        },

        remove(index) {
            this.participants.splice(index, 1);
        }
    }
}
</script>
@endpush
