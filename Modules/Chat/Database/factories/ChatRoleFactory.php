<?php

namespace Modules\Chat\Database\factories;

use Modules\Chat\Models\ChatRole;
use Modules\Chat\Models\Conversation;
use Modules\Chat\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatRoleFactory extends Factory
{
    protected $model = ChatRole::class;

    public function definition()
    {
        return [
            'conversation_id' => Conversation::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['admin', 'member']),
        ];
    }

    protected static function newFactory()
    {
        return ChatRoleFactory::new();
    }
    /**
     * Indstil en chatrolle som admin.
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    /**
     * Indstil en chatrolle som medlem.
     */
    public function member()
    {
        return $this->state(fn (array $attributes) => ['role' => 'member']);
    }
}
