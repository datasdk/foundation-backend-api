<?php

namespace Modules\Chat\Http\Controllers\Api;


use App\Http\Controllers\OrionBaseController;
use Modules\Chat\Models\User;
use Modules\Chat\Models\Conversation;
use Musonza\Chat\Facades\ChatFacade as Chat;
use Modules\Chat\Http\Resources\ChatResource;
use Modules\Chat\Events\ChatPusherMessage;
use Illuminate\Support\Facades\Log;
use Modules\Firebase\Services\NotificationService;
use Modules\Chat\Http\Requests\ChatRequest;
use Modules\Chat\Http\Requests\ChatSendRequest;
use Musonza\Chat\Models\Message;
use App\Http\Resources\BaseResource;
use Modules\Media\Services\MediaLibraryService;
use Orion\Http\Requests\Request;
use App\Http\Controllers\OrionRelationController;
use Modules\Chat\Events\MessageSent;



class MessageController extends OrionRelationController
{


    protected $model    = Conversation::class;
   // protected $resource = ChatResource::class;
    protected $request  = ChatRequest::class;
    protected $relation = 'messages';
    
    
/*
    protected $includes = [
        "messages", 
        "last_message", 
        "participants.messageable", 
        "participants"
    ];
*/

    protected $filterableBy = [
        "participants.messageable_id"
    ];

    protected $exposedScopes = [
        "IsMember"
    ];




    public function send(ChatSendRequest $req, $id)
    {
       
        $user_id = $req->user()->id;

        $user = User::find($user_id);

        $conversation = Conversation::findByNameOrId($id);


        if (!$conversation || !$conversation->hasParticipantByUserId($user->id)) {

            return response()->json(['error' => 'Chat not found or access denied'], 404);

        }


        $message = $req->message ?? ' ';

        $chat = Chat::message($message);

  


        if ($req->has('image') && $image = $req->file('image')) {

            // Upload image logic
            $file_name = uniqid();

            $mls = app(\Modules\Media\Services\MediaLibraryService::class);

            $media = $mls->uploadFile($user, $image, 'chat', 'images', $file_name);

     
            $src = $media->getPublicUrl();

            $thumb = $media->getThumbUrl();

        


            $chat->type('image')
                ->data([
                    'file_name' => $file_name,
                    'image_url' => $src ?? null,
                    'thumb_url' => $thumb ?? null
                ]);


        } else {

            $chat->type('text');

        }


        $chat->from($user)->to($conversation);

        $result = $chat->send();


        event(new MessageSent($conversation));


        try {

            broadcast(new ChatPusherMessage($conversation->id, $message, $src ?? null));

        } catch (\Exception $e) {

            Log::error("Chat broadcast error: " . $e->getMessage());

        }

      

        return [
            "content" => $result->body,
            "myself" => true,
            "participantId" => $user_id,
            "timestamp" => $result->created_at,
            "src" => $src ?? null,
            "thumb" => $thumb ?? null,
            "type" => $result->type,
            "uploaded" => true,
            "viewed" => false
        ];
    }

    
}
