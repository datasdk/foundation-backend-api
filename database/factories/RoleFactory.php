<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    // Angiv den model, denne factory hører til
    protected $model = \Spatie\Permission\Models\Role::class;

    public function definition()
    {

        $defaultGuard = config('auth.defaults.guard');


        return [
            'key_id' => $this->faker->uuid(),
            'name' => $this->faker->unique()->jobTitle(),
            'description' => $this->faker->sentence(10),
            'guard_name' => $defaultGuard, // fast værdi som i databasen
            'active' => $this->faker->boolean(90), // 90% chance for aktiv
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
