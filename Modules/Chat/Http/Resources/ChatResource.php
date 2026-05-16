<?php

namespace Modules\Chat\Http\Resources;


use App\Http\Resources\BaseResource;
use App\Models\User;
use Modules\Chat\Models\Conversation;

class ChatResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     public $preserveKeys = true;

 


    public function toArray($req)
    {

      if (!$this->resource instanceof Conversation) {
        abort(500, "The resource must be an instance of the Conversation class.");
      }
    
        
        

        $include = $req->include;
        $includes = explode(",",$include);
        //$include = "tasks.categories";
        $myself = true;//$req->user()->id === $this->participation_id;
        $res = $req;


        $participants = $this->participants->map(function($res){

          $m = $res->messageable;

          return [
            "name" => $m->first_name." ".$m->last_name,
            "id" => $res->messageable_id,
            "profilePicture" => $m->image ?? $this->getDefaultProfilePicture(),
         
          ];
          
        });

        $total_participants = $participants->count();

        $myself = $participants->filter(function($res){
          if($this->myself($res["id"])){ return true; }
        })->first();



        $other = $participants->filter(function($res){
          if(!$this->myself($res["id"])){ return true; }
        })->first();


        $total_messages = $this->messages()->count();

        $messagesPerPage = $req->has("limit") ? $req->get("limit") : config("chat.messagesPerPage");

        $messages = $this->messages()->orderByDesc("id")->paginate($messagesPerPage)
        ->reverse()->values()->map(function($res){

          $user_id = $res->participation->messageable_id;
          $src = null;


          if(isset($res->data["file_url"])){ 

            $src = $res->data["file_url"];

          }

          // return to map
          return [
            "id" => $res->id,
            "content" => $res->body,
            "myself" => $this->myself($user_id),
            "participantId" => $user_id,
            "timestamp" => $res->created_at,
            "src" => $src,
            "type" => $res->type,
            "uploaded" => true,
            "viewed" => true,
      
          ];
          
        });

        
 
        $result = [
            "id" => $this->id,
            "name" => $this->name,
            "private" => $this->private,
            "total_messages" => $total_messages,
            "total_participants" => $total_participants,
            "direct_message" => $this->direct_message,
            "data" => $this->data,
            "participants" => $participants,
            "myself" => $myself,
            "other" => $other,
            "settings" => $this->data, // is converted to settings
            "includes" => is_string($include) ? $this->load($includes) : null
        ];


        if(!empty($messages)){ $result["messages"] = $messages; }
      

        return  $result;
        
  
    }


    public function myself($id){

      return request()->user()->id === $id;

    }


    public function getDefaultProfilePicture(){

      return url("Modules/Chat/img/chat-no-user.jpg");

    }


}

/*

 
      $myUserId = $req->user()->id;


      if(!self::$header){


        $conversation = Conversation::find($this->conversation_id);
        
        $participants = $conversation->participants()->get()->map(function($o) use($myUserId){

          $m = $o->messageable;

          $myself = $myUserId === $m->id;
      
          return  [
            "id" => $m->id,
            "name" => $m->first_name." ".$m->last_name,  
            "profilePicture" => $m->image,
            "me" =>  $myself
          ];

        });


        $myself = $participants->filter(function($o){ return $o["me"] == true; })->first();

        $other = $participants->filter(function($o){ return $o["me"] != true; })->first();
        

        self::$header = $conversation->toArray() + 
        [ 
          "participants" => $participants->toArray(),

          "myself" => $myself,
            
          "other" => $other,
  
          "settings" => null,
        ];
   

      }



    

      
      return self::$header + [
        "messages" => [
          "id": $this->id,
          "content": $this->id,
          "myself": true,
          "participantId": 1,
          "timestamp": "2024-12-15T20:10:22.000000Z",
          "src": null,
          "type": "text",
          "uploaded": false,
          "viewed": true
        ]
      ];
      */