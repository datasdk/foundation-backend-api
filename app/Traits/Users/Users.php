<?php

namespace App\Traits\Users;

use User;

trait Users
{
    /**
     * Define a one-to-one relationship with the User model.
     *
     * This method defines a relationship where the current model has one associated user.
     * It links the current model's `user_id` field to the `id` field on the `User` model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
        // The "user_id" column on the current model is used to find the associated User by its "id".
    }
}
