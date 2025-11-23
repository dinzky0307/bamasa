<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'tourist',
            'remember_token' => Str::random(10),
        ];
    }

    // Admin user
    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => 'admin',
            'email' => 'admin@bantayan.test',
        ]);
    }

    // Business Owner
    public function businessOwner(): static
    {
        return $this->state(fn () => [
            'role' => 'business',
        ]);
    }
}
