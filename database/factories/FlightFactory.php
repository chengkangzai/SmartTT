<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use function array_rand;
use function rand;

class FlightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected string $model = Flight::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $airlineCount = Airline::count();
        $airportCount = Airport::count();
        return [
            'depart_time' => Carbon::now()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'arrive_time' => Carbon::tomorrow()->addDays(rand(1, 30))->addSeconds(rand(0, 100000)),
            'fee' => rand(10000, 200000),
            'airline_id' => rand(1, $airlineCount),
            'depart_airport' => rand(1, $airportCount),
            'arrival_airport' => rand(1, $airportCount),
            'flight_class' => array_rand(Flight::FCLASS),
            'flight_type' => array_rand(Flight::TYPE),
        ];
    }
}
