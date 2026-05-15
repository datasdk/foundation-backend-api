<?php

namespace Modules\Chat\Database\factories;

use Modules\Chat\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;


    protected static function newFactory()
    {
        return ConversationFactory::new();
    }

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'data' => $this->faker->optional()->text(200),
            'direct_message' => $this->faker->boolean,
        ];
    }
}
