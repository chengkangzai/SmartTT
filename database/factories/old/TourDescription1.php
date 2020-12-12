<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tour;
use App\Models\TourDescription;
use Faker\Generator as Faker;

$factory->define(TourDescription::class, function (Faker $faker) {
    $maxNumber = Tour::count();
    return [
        'place' => join(" ",$faker->words(3)),
        'description' => $faker->text,
        'tour_id' => rand(1, $maxNumber)
    ];
});
