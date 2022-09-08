<?php

namespace Database\Factories;

use App\Models\Airport;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class AirportFactory extends Factory
{
    protected $model = Airport::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'city' => $this->faker->city(),
            'IATA' => $this->faker->word(),
            'ICAO' => $this->faker->word(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'altitude' => $this->faker->randomNumber(),
            'offset_UTC' => $this->faker->word(),
            'DST' => $this->faker->word(),
            'timezoneTz' => $this->faker->word(),

            'country_id' => Country::factory(),
        ];
    }
}
