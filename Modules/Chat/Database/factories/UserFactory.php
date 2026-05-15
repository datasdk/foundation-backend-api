<?php
      
namespace Modules\Chat\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Chat\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;


    protected static function newFactory()
    {
        return ChatUserFactory::new();
    }

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Standard testkode
        ];
    }

    /**
     * Indstil en bruger som admin.
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    /**
     * Indstil en bruger som medlem.
     */
    public function member()
    {
        return $this->state(fn (array $attributes) => ['role' => 'member']);
    }
}
