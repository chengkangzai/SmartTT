<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Airline;
use App\Flight;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Flight::class, function (Faker $faker) {
    $airlineCount = Airline::count();
    return [
        'depart_time' => Carbon::now()->addDay(rand(1, 30)),
        'arrive_time' => Carbon::tomorrow()->addDay(rand(35, 50)),
        'fee' => rand(100, 3000),
        'airline_id' => rand(1, $airlineCount)
    ];
});
