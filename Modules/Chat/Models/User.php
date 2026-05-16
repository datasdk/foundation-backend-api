<?php

namespace Modules\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Musonza\Chat\Models\Participation;
use Musonza\Chat\Traits\Messageable;
use App\Models\User as OriginalUser;
use Modules\Media\Traits\InteractsWithMedia;
use Modules\Media\Contracts\HasMedia;


class User extends OriginalUser implements HasMedia
{

    use Messageable;
    use HasFactory;
    use InteractsWithMedia;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Get the class name of the morph class.
     *
     * @return string
     */
    public function getMorphClass()
    {
        return self::class;
    }


    /**
     * Get participant details.
     *
     * @return array
     */
    public function getParticipantDetails()
    {

        return [
            'id'   => $this->id,
            'name' => $this->first_name . " " . $this->last_name,
        ];

    }


    /**
     * Check if the user is a participant of a conversation.
     *
     * @param  string  $name
     * @return bool
     */
    public function isParticipantOf($name)
    {
        return $this->conversations()
            ->where('name', $name)
            ->exists();
    }


}
