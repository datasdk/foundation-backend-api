<?php

namespace Modules\Chat\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Chat\Models\User;
use Modules\Chat\Models\Conversation;

class MessageSent
{
    use Dispatchable, SerializesModels;


    public Conversation $conversation;
    public $me;
    public $others;


    public function __construct(Conversation $conversation)
    {

        $this->conversation = $conversation;
        $this->me = $conversation->me;
        $this->others = $conversation->others;
    
    }
}
