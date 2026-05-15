<?php

namespace Modules\Chat\Http\Controllers\Api;


use App\Http\Controllers\OrionBaseController;
use Modules\Chat\Models\User;
use Modules\Chat\Models\Participation;


class MembersController extends OrionBaseController
{

    protected $model = Participation::class;


    protected $includes = [
        "user",
        "user.company"
    ];
    
    

}
