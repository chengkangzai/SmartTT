<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Tour;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use function rand;

class TripFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tourCount = Tour::count();
        return [
            'fee' => (int)rand(1000, 2000) . "00",
            'tour_id' => rand(1, $tourCount),
            'capacity' => rand(25, 30),
            'airline' => Airline::inRandomOrder()->first()->name,
            'depart_time' => Carbon::now(),
        ];
    }
}
