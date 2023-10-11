<?php

namespace Database\Factories;

use App\Models\Seat;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    protected $model = Seat::class;

    public function definition()
    {
        $trip = Trip::factory()->create();

        return [
            'trip_id' => $trip->id,
            'seat_number' => $this->faker->numberBetween(1, 12),
        ];
    }
}
