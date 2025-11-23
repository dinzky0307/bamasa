<?php

namespace Database\Factories;

use App\Models\Attraction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AttractionFactory extends Factory
{
    protected $model = Attraction::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Sugar Beach',
            'Kota Beach',
            'Ogtong Cave',
            'Sts. Peter and Paul Church',
            'Virgin Island',
            'Hilantagaan Island',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . $this->faker->unique()->numberBetween(1, 9999)),
            'type' => $this->faker->randomElement(['beach', 'church', 'cave', 'island', 'landmark']),
            'description' => $this->faker->paragraph(),
            'municipality' => $this->faker->randomElement(['Bantayan', 'Santa Fe', 'Madridejos']),
            'address' => $this->faker->streetAddress(),
            'latitude' => $this->faker->randomFloat(7, 11.0, 11.5),   // fake values
            'longitude' => $this->faker->randomFloat(7, 123.5, 124.5), // fake values
            'thumbnail' => null,
            'is_featured' => $this->faker->boolean(30),
        ];
    }
}
