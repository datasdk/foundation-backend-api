<?php

namespace Modules\Chat\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Chat\Models\Conversation;
use Modules\Chat\Models\User;



class ChatApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test at en autentificeret bruger kan oprette en ny chat.
     */
    public function test_authenticated_user_can_create_chat()
    {

        $user = User::factory()->create();

        $this->actingAs($user);

        $data = [
            'name' => 'Test Chat',
            'join' => true,
        ];

        $response = $this->postJson(route('api.chats.conversations.store'), $data);

        $response->assertStatus(201);
    

        $this->assertDatabaseHas('chat_conversations', ['name' => 'Test Chat']);
    }

    /**
     * Test at en autentificeret bruger kan opdatere en chat.
     */
    public function test_authenticated_user_can_update_chat()
    {

        $user = User::factory()->create();
    
        
        $this->actingAs($user);

        $conversation = Conversation::create(['name' => 'Initial Chat']);

        $data = ['join' => true];

        $response = $this->putJson(route('api.chats.conversations.update', $conversation->id), $data);
        $response->assertStatus(200);


        $this->assertTrue($conversation->refresh()->hasParticipant($user));
        
    }

    /**
     * Test at en autentificeret bruger kan slette en chat.
     */
    public function test_authenticated_user_can_destroy_chat()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $conversation = Conversation::create(['name' => 'Initial Chat']);

        $response = $this->deleteJson(route('api.chats.conversations.destroy', $conversation->id));

        $response->assertNoContent();
      

        $this->assertDatabaseMissing('chat_conversations', ['id' => $conversation->id]);
    }


    public function test_can_add_participants()
    {
        // Opret en testbruger
        $user = User::factory()->create();

        // Opret en samtale
        $conversation = Conversation::create(['name' => 'Initial Chat']);

        // Tilføj deltager
        $conversation->addParticipants([$user]);

        // Bekræft at brugeren er tilføjet som deltager
        $this->assertTrue($conversation->hasParticipant($user));

        // Tjek om relationen eksisterer i databasen
        $this->assertDatabaseHas('chat_participation', [
            'messageable_id' => $user->id,
            'conversation_id' => $conversation->id
        ]);

    }


    public function test_can_remove_participants()
    {
        // Opret en testbruger
        $user = User::factory()->create();

        // Opret en samtale
        $conversation = Conversation::create(['name' => 'Initial Chat']);

        // Tilføj deltager
        $conversation->addParticipants([$user]);
        
        $conversation->removeParticipant([$user]);

        // Bekræft at brugeren er tilføjet som deltager
        $this->assertFalse($conversation->hasParticipant($user));

        // Tjek om relationen eksisterer i databasen
        $this->assertDatabaseMissing('chat_participation', [
            'messageable_id' => $user->id,
            'conversation_id' => $conversation->id
        ]);

    }
    /**
     * Test at en autentificeret bruger kan sende en besked i en chat.
     */
    public function test_authenticated_user_can_send_message()
    {

        $user = User::factory()->create();

        $this->actingAs($user);

        $conversation = Conversation::create(['name' => 'Initial Chat']);
            
        $conversation->addParticipants([$user]);


        $data = ['message' => 'Hello, world!'];

        $url = url("api/chats/{$conversation->id}/message/send");
        
        $response = $this->postJson($url, $data);

        $response->assertStatus(200);

      
    }
}
