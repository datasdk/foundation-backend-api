<?php

namespace Modules\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Musonza\Chat\Models\Conversation as OrigConversation;
use Musonza\Chat\Traits\Messageable;
use Modules\Chat\Models\User as ParticipationUser;
use Modules\Chat\Models\Participation;
use Modules\Media\Traits\InteractsWithMedia;
use Modules\Media\Contracts\HasMedia;
use Modules\Chat\Http\Scopes\ChatMembers;

class Conversation extends OrigConversation implements HasMedia
{

    use ChatMembers;
    // Use the Messageable trait to enable messaging functionality
    use Messageable;
    // Use HasFactory to allow factory support for the model
    use HasFactory;
    // Use InteractsWithMedia to add media functionality (e.g., images, documents)
    use InteractsWithMedia;

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'name', 
        'title', 
        'description', 
        'data', 
        'direct_message'
    ];

    protected $table = "chat_conversations";
    
     protected $casts = [
        'data' => 'array',
    ];

    protected $hidden = [
        
    ];

    /**
     * Get the class name of the morph class.
     *
     * @return string
     */
    public function getMorphClass()
    {
        return Conversation::class;
    }

    /**
     * Find a conversation by its name or ID.
     *
     * @param  mixed  $nameOrId
     * @return \Modules\Chat\Models\Conversation|null
     */
    public static function findByNameOrId($nameOrId)
    {
      
        // If the input is an integer, find by ID; otherwise, find by name
        return is_numeric($nameOrId) 
            ? self::findOrFail($nameOrId) 
            : self::findByName($nameOrId);
    }

    /**
     * Find a conversation by its name.
     *
     * @param  string  $name
     * @return \Modules\Chat\Models\Conversation|null
     */
    public static function findByName($name)
    {
        return self::where(["name" => $name])->first();
    }

    /**
     * Check if a conversation with the given name exists.
     *
     * @param  string  $name
     * @return bool
     */
    public static function exists($name)
    {
        return self::where(["name" => $name])->exists();
    }

    /**
     * Get the participant of the conversation (excluding the current user).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */



    public function others()
    {
        // Get the current user's ID from the request
        $userId = request()->user()->id;

        // Return the first participant excluding the current user
        return $this->hasMany(Participation::class)->where("messageable_id", "!=", $userId);

    }


    public function me()
    {
        // Get the current user's ID from the request
        $userId = request()->user()->id;

        // Return the first participant excluding the current user
        return $this->hasOne(Participation::class)->where("messageable_id", "=", $userId);
    }

    /**
     * Check if the conversation has the given participant user.
     *
     * @param  \Modules\Chat\Models\User  $user
     * @return bool
     */
    public function hasParticipant(ParticipationUser $user)
    {
        return $this->participants()->where("messageable_id", $user->id)->exists();
    }

    /**
     * Check if the conversation has a participant by user ID.
     *
     * @param  int  $user_id
     * @return bool
     */
    public function hasParticipantByUserId(int $user_id)
    {
        // Find the user by ID, return false if not found
        if (!$user = ParticipationUser::find($user_id)) {
            return false;
        }

        // Check if the conversation has this user as a participant
        return $this->hasParticipant($user);
    }

 
}
