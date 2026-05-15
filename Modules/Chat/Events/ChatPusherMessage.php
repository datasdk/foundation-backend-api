<?php

namespace Modules\Chat\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatPusherMessage implements ShouldBroadcastNow {

    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public $chat_id;

    public $message;

    public $image;

    public $user_id;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($chat_id,$message,$image)
    {
     
        $this->chat_id = $chat_id;
        $this->message = $message;
        $this->image = $image;
        $this->user_id = auth()->user()->id;
        $this->uploaded = true;
        $this->viewed = true;
  
    }


    public function broadcastWith(): array
    {

        return [
            'chat_id' => $this->chat_id,    
            'message' => $this->message,
            'image' => $this->image,
            'user_id' => $this->user_id,
            'uploaded' => true,
            'viewed' => true,
        ];

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        return new Channel('chat-channel.'.$this->chat_id);

    }


    public function broadcastAs()
    {
      
        return 'messages';
      // return 'chat.user_id.'.$this->user_id;

    }

}
