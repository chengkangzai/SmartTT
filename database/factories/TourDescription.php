<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tour;
use App\TourDescription;
use Faker\Generator as Faker;

$factory->define(TourDescription::class, function (Faker $faker) {
    $maxNumber = Tour::all()->count();
    return [
        'place' => $faker->name,
        'description' => $faker->name,
        'tour_id' => rand(1, $maxNumber)
    ];
});
