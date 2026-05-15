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


class ConversationController extends OrionBaseController
{

    protected $model = Conversation::class;
  //  protected $resource = ChatResource::class;
    protected $request = ChatRequest::class;
    protected $relation = 'messages';
  //  protected $keyname = "name";
    

    protected $includes = [
        "messages", 
        "last_message", 
        "participants.messageable", 
        "participants"
    ];


    protected $filterableBy = [
        "participants.messageable_id"
    ];

    protected $exposedScopes = [
        "IsMember"
    ];


    public function store(Request $req)
    {

        try {


            $chatName = $req->name;
            
            $conversation = Conversation::firstOrCreate(["name" => $chatName]);
            
            $invites = [];

            $user = User::find($req->user()->id);


            if ($req->boolean("join") && $user && !$conversation->hasParticipant($user)) {

                $invites[] = $user;

            }


            if ($req->has("invite")) {


                foreach ($req->invite as $user_id) {

                    $invite = User::find($user_id);


                    if ($invite && !$conversation->hasParticipant($invite)) {

                        $invites[] = $invite;

                    }

                }

            }


            $conversation->addParticipants($invites)->makePrivate(true);


            return new $this->resource($conversation);



        } catch (\Exception $e) {


            Log::error("Chat store error: " . $e->getMessage());

            return response()->json(['error' => 'Something went wrong'], 500);


        }

    }


    public function update(Request $req, ...$args)
    {
        try {
            
            $id = $args[0];

            $conversation = Conversation::findByNameOrId($id);

            if (!$conversation) {

                return response()->json(['error' => 'Chat not found'], 404);

            }


            $user = User::find($req->user()->id);


            if ($req->has("join") && $user) {


                if($req->boolean("join")){

                    if(!$conversation->hasParticipant($user)){

                        $conversation->addParticipants([$user]);

                    }
                    
                } else {

                    $conversation->removeParticipant([$user]);

                }
            
            }

   

            if ($req->has("invite")) {

                $invites = array_filter(array_map(fn($user_id) => User::find($user_id), $req->invite), fn($invite) => $invite && !$conversation->hasParticipant($invite));
                
                if (!empty($invites)) {

                    Chat::conversation($conversation)
                    ->addParticipants($invites);

                }

            }
            

            return new $this->resource($conversation);


        } catch (\Exception $e) {


            Log::error("Chat update error: " . $e->getMessage());

            return response()->json(['error' => 'Something went wrong'], 500);


        }

    }


    public function destroy(Request $req, ...$args)
    {

        try {
        
            $id = $args[0];

            $conversation = Conversation::findByNameOrId($id);

            if (!$conversation) {

                return response()->json(['error' => 'Chat not found'], 404);

            }


            $conversation->participants()->delete();

            $conversation->delete();

            
            return response()->noContent();


        } catch (\Exception $e) {

            Log::error("Chat destroy error: " . $e->getMessage());

            return response()->json(['error' => 'Something went wrong'], 500);

        }

    }

}
