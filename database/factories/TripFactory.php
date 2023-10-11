<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\City;
use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition()
    {
        $startCity = City::factory()->create();
        $endCity = City::factory()->create();
        $bus = Bus::factory()->create();

        return [
            'start_city_id' => $startCity->id,
            'end_city_id' => $endCity->id,
            'bus_id' => $bus->id,
        ];
    }
}
