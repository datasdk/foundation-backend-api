<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use DataSDK\Addresses\Models\Address;
use DataSDK\Addresses\Models\Contact;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'uid' => Str::uuid(),
            'image' => null,
            'username' => $this->faker->userName,
            'first_name' => $this->faker->firstName,
            'middle_name' => null,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'type' => 'default',
            'remember_token' => Str::random(10),
            'lastLoggedIn' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    
    public function unverified()
    {

        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);

    }

    public function configure()
    {

        return $this->afterCreating(function (User $user) {

            Address::factory()->create([
                'addressable_type' => User::class,
                'addressable_id' => $user->id,
            ]);

            Contact::factory()->create([
                'contactable_type' => User::class,
                'contactable_id' => $user->id,
            ]);

        });

    }
}
