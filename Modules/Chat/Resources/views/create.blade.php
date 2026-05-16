@extends('layouts.app')

@section('content')

    <h1>Opret ny chat</h1>

    <form method="POST" action="{{ route('chat.store') }}">
        @csrf

        <table class="table table-bordered">
            <tbody>
                <!-- Titel -->
                <tr>
                    <td width="150">Titel</td>
                    <td>
                        <input type="text" class="form-control" name="title" required>
                    </td>
                </tr>

                <!-- Beskrivelse -->
                <tr>
                    <td>Beskrivelse</td>
                    <td>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </td>
                </tr>

                <!-- Direkte besked -->
                <tr>
                    <td>Direkte besked</td>
                    <td>
                        <input type="checkbox" class="form-check-input" name="direct_message" value="1">
                    </td>
                </tr>

                <!-- Chat medlemmer -->
                <tr>
                    <td>Chat deltagere</td>
                    <td>
                        <div id="participants" x-data="chatParticipants()">
                            <template x-for="(participant, index) in participants" :key="index">
                                <div class="row g-2 mb-2 align-items-center">
                                    <div class="col-md-10">
                                        <livewire:select-user 
                                            :key="index" 
                                            name="participants[index][user_id]" 
                                            wire:model.defer="participants[index].user_id"
                                        />
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100" @click="remove(index)">Fjern</button>
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
            <button type="submit" class="btn btn-primary">Opret chat</button>
            <a href="{{ route('chat.index') }}" class="btn btn-secondary">Tilbage</a>
        </div>
    </form>

@endsection

@push('scripts')
<script>
function chatParticipants() {
    return {
        participants: [],

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
