<?php

namespace Modules\Chat\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;


class SendNotification
{
    use Dispatchable, SerializesModels;


    public array $notification;
 
    /**
     * Create a new event instance.
     */
    public function __construct(array $notification)
    {
        $this->notification = $notification;
    
    }

}
