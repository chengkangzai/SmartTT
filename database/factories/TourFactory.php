<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tour;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

$factory->define(Tour::class, function (Faker $faker) {
    return [
        'tour_code' => $faker->text,
        'name' => $faker->word,
        'destination' => $faker->city,
        'category' => $faker->country,
        'itinerary_url' => $faker->imageUrl(300,200),
        'thumbnail_url' => $faker->imageUrl(300,200),
    ];
});
