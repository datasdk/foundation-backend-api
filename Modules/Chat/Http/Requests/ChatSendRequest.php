<?php

namespace Modules\Chat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatSendRequest extends FormRequest
{
    // Validation rules for sending a chat message
    public function rules(): array
    {
        return [
            "message" => "required_without:image",
            "url" => "sometimes|string",
            "image" => "sometimes|image|mimes:" . $this->allowedMimes() , // Max 5MB

            "notification" => "sometimes|array",
            "notification.draft_id" => "sometimes|required_without_all:notification.title,notification.body|exists:Firebase_drafts,id",
            "notification.title" => "sometimes|required_without:notification.draft_id|string",
            "notification.body" => "sometimes|required_without:notification.draft_id|string",
            "notification.url" => "sometimes|string",
        ];
    }

    // Helper method to get allowed MIME types from config
    private function allowedMimes(): string
    {
        return implode(',', config('filemanager.allowed_image_extensions', ['jpg', 'jpeg', 'png', 'gif']));
    }

    public function messages(): array
    {
        return [
            // Message fields
            'message.required_without:image' => 'Beskedfeltet eller billede er påkrævet',
            
            // URL field
            'url.sometimes' => 'URL-feltet er valgfrit.',
            'url.string' => 'URL-feltet skal være en gyldig tekststreng.',
            
            // Image fields
            'image.required_without:message' => 'Beskedfeltet eller billede er påkrævet',
            'image.mimes' => 'Billedet skal være en af følgende typer: ' . $this->allowedMimes(),
            'image.max' => 'Billedet må ikke overstige 5MB.',
            
            // Notification fields
            'notification.sometimes' => 'Notifikationsfeltet er valgfrit.',
            'notification.array' => 'Notifikationer skal være i form af en liste.',
            
            'notification.draft_id.required_without:notification.title,notification.body' => 'Angiv draft_id eller title og body',
            'notification.title.required_without:notification.draft_id' => 'Angiv draft_id eller title og body',
            'notification.body.required_without:notification.draft_id' => 'Angiv draft_id eller title og body',

            'notification.draft_id.integer' => 'Udkast-id skal være et gyldigt heltal.',
            'notification.url.sometimes' => 'URL i notifikation er valgfrit.',
            'notification.url.string' => 'URL i notifikation skal være en gyldig tekststreng.',
        ];
    }
}
