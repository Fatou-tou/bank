<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            // 'role' => $this->faker->randomElement(['admin', 'super_admin']),
            'role' => $this->faker->randomElement(['admin']),
            'actif' => $this->faker->boolean(90), // 90% chance d'être actif
        ];
    }
}
