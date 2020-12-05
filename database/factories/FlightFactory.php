<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
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
    public function definition()
    {
        $airlineCount = Airline::count();
        return [
            'depart_time' => Carbon::now()->addDay(rand(1, 30)),
            'arrive_time' => Carbon::tomorrow()->addDay(rand(35, 50)),
            'fee' => rand(100, 3000),
            'airline_id' => rand(1, $airlineCount)
        ];
    }
}
