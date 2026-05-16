<?php

use Illuminate\Http\Request;
use Orion\Facades\Orion;


Route::group([
    'as' => 'api.chats.',
    'middleware' => 'auth:api',
    'prefix' => 'chats'
], function ($router) {


    Orion::resource("conversations","Api\ConversationController");

    Orion::resource("members","Api\MembersController");

    
    Orion::hasManyResource('conversations', 'messages', "Api\MessageController");
        
    Route::post("{id}/message/send","Api\MessageController@send");



});

