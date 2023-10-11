<?php

namespace Database\Factories;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    protected $model = Bus::class;

    public function definition()
    {
        return [
            'registration_number' => $this->faker->unique()->regexify('[A-Z0-9]{7}'),
        ];
    }
}
