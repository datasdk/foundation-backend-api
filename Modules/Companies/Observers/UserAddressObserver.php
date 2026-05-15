<?php

namespace Modules\Companies\Observers;

use App\Models\User;
Use Modules\Companies\Models\Addresses;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class UserAddressObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */

     

    public function saving(User $user){
        

        $req = request();
     

        $validator = Validator::make($req->all(), [
            "address" => "sometimes|nullable|array",
            "address.street" => "sometimes|nullable",
            "address.city" => "sometimes|nullable",
            "address.state" => "sometimes|nullable",
            "address.post_code" => "sometimes|nullable",
           // "address.country_id " => "sometimes|nullable|int",
        ]);

        if ($validator->fails()) {
            return false;
        }



        if($req->has("address.street")){

            Addresses::add($user->id,$params = $req->get("address"));
 
        }

   
    }


    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function update(User $user)
    {
        
        
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
