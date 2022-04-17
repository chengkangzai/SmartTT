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
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Flight::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['depart_time' => "\Illuminate\Support\Carbon", 'arrive_time' => "\Illuminate\Support\Carbon", 'fee' => "int", 'airline_id' => "int", 'departure_airport' => "int", 'arrival_airport' => "int", 'class' => "array|int|string", 'type' => "array|int|string"])]
    public function definition(): array
    {
        $airlineCount = Airline::count();
        $airportCount = Airport::count();
        return [
            'depart_time' => now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'arrive_time' => now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'fee' => rand(10000, 200000),
            'airline_id' => rand(1, $airlineCount),
            'departure_airport' => rand(1, $airportCount),
            'arrival_airport' => rand(1, $airportCount),
            'class' => array_rand(Flight::FCLASS),
            'type' => array_rand(Flight::TYPE),
        ];
    }
}
