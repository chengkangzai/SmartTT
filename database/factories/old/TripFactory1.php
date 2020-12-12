<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Airline;
use App\Models\Tour;
use App\Models\Trip;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Trip::class, function (Faker $faker) {
    $tourCount = Tour::count();
    return [
        'fee' => (int)rand(1000, 2000) . "00",
        'tour_id' => rand(1, $tourCount),
        'capacity' => rand(25, 30),
        'airline' => Airline::inRandomOrder()->first()->name,
        'depart_time' => Carbon::now(),
    ];
});
