<?php

namespace Modules\Chat\Listeners;

use Modules\Chat\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Modules\Firebase\Events\SendNotification;

class SendChatNotification
{
    
    public function handle(MessageSent $event)
    {
        
        $conversation = $event->conversation;

        $me = $event->me->user;

        $others = $event->others;



        foreach($others as $other){

            $user = $other->user;

            $params = [
                "user_id" => $user->id,
                "title" => "Ny chat-besked fra ".$me->first_name,
                "body" => "Åbn appen for at læse mere",
                "send_after" => now()->addHours(1)
            ];

            
            event(new SendNotification($params));

        } 

       
    }

}
