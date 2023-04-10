<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'address' => fake()->address(),
        ];
    }

    /**
     * Indicate that the model's email password name and address should be undefined (Guest user).
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => null,
            'email' => null,
            'password' => null,
            'address' => null,
        ]);
    }

    /**
     * Indicate that the model's email should be customized
     */
    public function customEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'javier@smartidea.es',
        ]);
    }

    /**
     * Indicate that the model's email should be malformed
     */
    public function malformedEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'javier@smartidea',
        ]);
    }

}
