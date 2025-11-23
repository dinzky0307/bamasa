<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->businessOwner(),
            'name' => $this->faker->company() . ' Resort',
            'category' => $this->faker->randomElement(['resort', 'hotel', 'homestay', 'restaurant', 'tour']),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->streetAddress(),
            'municipality' => $this->faker->randomElement(['Bantayan', 'Santa Fe', 'Madridejos']),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'website' => $this->faker->url(),
            'facebook_page' => 'https://facebook.com/' . $this->faker->slug(),
            'status' => 'approved',
            'min_price' => 1000,
            'max_price' => 5000,
        ];
    }
}
