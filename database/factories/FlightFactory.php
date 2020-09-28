<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Airline;
use App\Flight;
use App\Trip;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Flight::class, function (Faker $faker) {
    return [
        'depart_time' => Carbon::now(),
        'arrive_time' => Carbon::tomorrow(),
        'fee' => rand(100, 3000),
    ];
});
