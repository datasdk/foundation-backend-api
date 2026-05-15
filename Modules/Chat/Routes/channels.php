<?php
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;


Broadcast::channel('chat-channel.{chat_id}', function ($user,int $chat_id) {

    return true;
});

