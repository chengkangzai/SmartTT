<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function array_rand;
use function rand;

class FlightFactory extends Factory
{
    protected $model = Flight::class;

    #[ArrayShape(['departure_date' => "\Illuminate\Support\Carbon", 'arrival_date' => "\Illuminate\Support\Carbon", 'price' => "int", 'airline_id' => "int", 'departure_airport_id' => "int", 'arrival_airport_id' => "int", 'class' => "array|int|string", 'type' => "array|int|string"])]
    public function definition(): array
    {
        $airlineCount = Airline::count();
        $airportCount = Airport::count();
        return [
            'departure_date' => now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'arrival_date' => now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'price' => rand(100, 2000),
            'airline_id' => rand(1, $airlineCount),
            'departure_airport_id' => rand(1, $airportCount),
            'arrival_airport_id' => rand(1, $airportCount),
            'class' => array_rand(Flight::FCLASS),
            'type' => array_rand(Flight::TYPE),
        ];
    }
}
