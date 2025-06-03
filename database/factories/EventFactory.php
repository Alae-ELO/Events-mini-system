<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Place;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $organizer = User::factory()->create([
            'role' => 'organizer'
        ]);
        $title = $this->faker->sentence(3);
        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+3 months');

        return [
            'title' => $title,
            'description' => $this->faker->paragraphs(3, true),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'capacity' => $this->faker->numberBetween(10, 1000),
            'organizer_id' => $organizer->id,
            'place_id' => Place::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
