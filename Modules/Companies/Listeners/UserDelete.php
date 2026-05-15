<?php

namespace Modules\Companies\Listeners;

use Illuminate\Http\Request;
use App\Models\User;
use Modules\Companies\Events\StoreEvents;
use Modules\Companies\Http\Requests\CompanyRequest;
use Modules\Companies\Models\Companies;

class UserDelete
{
    /**
     * Handle the event.
     *
     * @param  StoreEvents  $event
     * @return void
     */
    public function handle(Request $req, User $user)
    {
    
        if($req->has("company")){

            return Companies::create($req->get("company"));

        }

    }
}
