<?php

namespace Modules\Chat\Http\Requests;

use Orion\Http\Requests\Request;
use Modules\Crm\Rules\StorageFileExists;

class ChatRequest extends Request
{
    // Validation rules for storing a chat
    public function storeRules(): array
    {
        return [
            "name" => "required|string|max:255",
            "invite" => "sometimes|array",
            "invite.*" => "sometimes|integer|exists:users,id",
            "join" => "sometimes|boolean",
            "data" => "sometimes",
        ];
    }

    // Validation rules for updating a chat
    public function updateRules(): array
    {
        return [
            "join" => "sometimes|boolean",
            "invite" => "sometimes|array",
            "invite.*" => "sometimes|integer|exists:users,id",
        ];
    }

    public function messages(): array
    {
        return [
            // Chat fields
            "name.required" => "Navnet på chatten er påkrævet.",
            "name.string" => "Navnet på chatten skal være en tekst.",
            "name.max" => "Navnet på chatten må ikke overstige 255 tegn.",
            
            // Invite fields
            "invite.array" => "Invitationsfeltet skal være en liste.",
            "invite.*.integer" => "Hver invitation skal være et gyldigt bruger-id.",
            "invite.*.exists" => "Brugeren, du prøver at invitere, findes ikke i systemet.",
            
            // Join fields
            "join.boolean" => "Deltagerfeltet skal være sandt eller falsk.",
            
            // Data field
            "data.sometimes" => "Data-feltet er valgfrit.",
        ];
    }
}
