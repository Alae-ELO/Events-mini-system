<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $names = ['Sport', 'Musique', 'Art', 'Cuisine'];
        $name = $this->faker->unique()->randomElement($names);
        
        return [
            'name' => $name,
            'description' => $this->faker->sentence(),
        ];
    }
} 