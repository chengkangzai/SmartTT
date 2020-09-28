<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Airline;
use App\Flight;
use App\Tour;
use App\Trip;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Trip::class, function (Faker $faker) {
    $tourCount = Tour::all()->count();
    $flightCount = Flight::all()->count();
    return [
        'fee' => (int)rand(1000, 2000) . "00",
        'tour_id' => rand(1, $tourCount),
        'capacity' => rand(25, 30),
        'airline' => Airline::inRandomOrder()->first()->name,
        'flight_id' => rand(0, $flightCount),
        'depart_time' => Carbon::now(),
    ];
});
