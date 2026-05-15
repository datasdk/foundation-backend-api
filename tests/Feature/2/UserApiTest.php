<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserApiTest extends TestCase
{
    use WithFaker;
    use DatabaseMigrations;

 

    public function test_create_user()
    {
        $payload = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(route('api.users.store'), $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'first_name', 'last_name', 'email']);
                 
    }

    public function test_get_users()
    {

        $user = $this->createUser();

        $response = $this->actingAs($user)->getJson(route('api.users.show', $user->id));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => ['id', 'first_name', 'last_name', 'email']
                 ]);

    }


    public function test_update_user()
    {
        $user = $this->createUser();

        $payload = ['first_name' => 'UpdatedName'];

        $response = $this->actingAs($user)->patchJson(route('api.users.update', $user->id), $payload);

        $response->assertStatus(200);

    }


    public function test_cant_delete_last_user()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->deleteJson(route('api.users.destroy', $user->id));

        $response->assertStatus(400);
    }


    public function test_delete_user()
    {

        $user = $this->createUser();

        $user2 = $this->createUser();


        $response = $this->actingAs($user)->deleteJson(route('api.users.destroy', $user2->id));

        
        $response->assertNoContent();

        $this->assertDatabaseMissing('users', ['id' => $user2->id]);

    }
    
}
