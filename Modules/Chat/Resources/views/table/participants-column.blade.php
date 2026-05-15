@if($participants && $participants->count())
    <ul class="list-disc pl-4 text-sm">
        @foreach($participants as $participant)
            @php
                $user = $participant->messageable;
                $name = $user->name ?? trim("{$user->first_name} {$user->last_name}");
            @endphp
            <li>{{ $name }}</li>
        @endforeach
    </ul>
@else
    <span class="text-gray-400">-</span>
@endif
