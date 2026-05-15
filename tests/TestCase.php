<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;


abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;
 


    public function createUser(array $params = [])
    {

        $params["email_verified_at"] = now();

        $user = User::factory()->create($params);

        $user->setPassword("123456");


        return $user;
 
        
    }
  

}
