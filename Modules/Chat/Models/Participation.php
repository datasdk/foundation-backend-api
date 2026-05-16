<?php

namespace Modules\Chat\Models;

use Musonza\Chat\Models\Participation as OrigParticipation;
use App\Models\User;
//use Modules\Chat\Models\User;

class Participation extends OrigParticipation
{
    
    protected $fillable = [
        'conversation_id',
        'messageable_id',
        'messageable_type',
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'messageable_id');
    }

    

}
