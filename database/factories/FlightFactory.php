<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Settings\FlightSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightFactory extends Factory
{
    protected $model = Flight::class;

    public function definition(): array
    {
        $flightSetting = app(FlightSetting::class);
        $airport = Airport::count() > 2
            ? Airport::inRandomOrder()->take(2)->get()
            : Airport::factory()->count(2)->create();

        /** @var Airline $airline */
        $airline = Airline::count() > 1
            ? Airline::inRandomOrder()->first()
            : Airline::factory()->create();

        return [
            'departure_date' => now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'arrival_date' => now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'price' => rand(100, 2000) * 100,
            'airline_id' => $airline->id,
            'airline' => $airline->name,
            'departure_airport_id' => $airport[0]->id,
            'arrival_airport_id' => $airport[1]->id,
            'class' => $flightSetting->supported_class[array_rand($flightSetting->supported_class)],
            'type' => $flightSetting->supported_type[array_rand($flightSetting->supported_type)],
        ];
    }
}
