<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    private static $usedNames = [];

    public function definition()
    {
        $names = ['Sport', 'Musique', 'Art', 'Cuisine'];
        return [
            'name' => $this->faker->randomElement($names)->unique(),
            'description' => $this->faker->sentence(),
        ];
    }
} 