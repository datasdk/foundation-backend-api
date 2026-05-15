<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;


class AuthApiTest extends TestCase
{

    use DatabaseMigrations;

    protected $user;


    protected function setUp(): void
    {
        parent::setUp();
        
       // Artisan::call("migrate");
    
       
        app()->setLocale('da');

        // Opret en bruger til test
        $this->user = $this->createUser();

    }

    /** @test */
    public function user_can_log_in()
    {

        $response = $this->postJson(route('api.user.login'), [
            "email" => $this->user->email,
            "password" => "123456",
        ]);

        $response->assertStatus(200);

    }


    /** @test */
    public function user_can_log_out()
    {

        $response = $this->actingAs($this->user)->postJson(route('api.user.logoff'));

        $response->assertStatus(200);

    }

    /** @test */
    public function user_can_refresh_token()
    {
        $response = $this->actingAs($this->user)->postJson(route('api.user.refresh-token'));

        $response->assertStatus(200);
    }


    /** @test */
    public function user_can_request_password_reset()
    {

        $response = $this->postJson(route('api.password.request'), [
            "email" => $this->user->email,
        ]);

        $response->assertStatus(200);

    }

    /** @test */
    public function user_can_resend_activation_email()
    {
        
        Artisan::call("db:seed");

        $response = $this->postJson(route("api.resend-activation-email"), [
            "email" => $this->user->email,
        ]);
    
        $response->assertStatus(200);

    }


    /** @test */
    public function user_can_resend_invitation()
    {
        
        Artisan::call("db:seed");

        $response = $this->postJson(route("api.resend-invitation"), [
            "email" => $this->user->email,
        ]);
        
        $response->assertStatus(200);
        
    }
    
}
