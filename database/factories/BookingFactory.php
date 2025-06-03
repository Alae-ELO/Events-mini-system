<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $explorer = User::factory()->create([
            'role' => 'explorer'
        ]);
        $quantity = $this->faker->numberBetween(1, 5);
        $event = Event::factory()->create();
        $totalPrice = $quantity * $event->price;

        return [
            'explorer_id' => $explorer->id,
            'event_id' => $event->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled'])
        ];
    }
}
