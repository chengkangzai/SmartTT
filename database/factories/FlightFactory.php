<?php

namespace Database\Factories;

use function app;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Settings\FlightSetting;
use function array_rand;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function rand;

class FlightFactory extends Factory
{
    protected $model = Flight::class;

    #[ArrayShape(['departure_date' => "\Illuminate\Support\Carbon", 'arrival_date' => "\Illuminate\Support\Carbon", 'price' => "int", 'airline_id' => "int", 'departure_airport_id' => "int", 'arrival_airport_id' => "int", 'class' => "array|int|string", 'type' => "array|int|string"])]
    public function definition(): array
    {
        $flightSetting = app(FlightSetting::class);
        $airport = Airport::inRandomOrder()->take(2)->get();

        return [
            'departure_date' => now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'arrival_date' => now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'price' => rand(100, 2000),
            'airline_id' => Airline::inRandomOrder()->first()->id,
            'departure_airport_id' => $airport[0]->id,
            'arrival_airport_id' => $airport[1]->id,
            'class' => $flightSetting->supported_class[array_rand($flightSetting->supported_class)],
            'type' => $flightSetting->supported_type[array_rand($flightSetting->supported_type)],
        ];
    }
}
