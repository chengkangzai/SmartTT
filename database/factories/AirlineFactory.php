<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class AirlineFactory extends Factory
{
    protected $model = Airline::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'ICAO' => $this->faker->word(),
            'IATA' => $this->faker->word(),

            'country_id' => Country::factory(),
        ];
    }
}
