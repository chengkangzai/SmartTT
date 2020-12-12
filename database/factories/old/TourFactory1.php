<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tour;
use Faker\Generator as Faker;

$factory->define(Tour::class, function (Faker $faker) {
    $selection = ['Asia', 'Arabic', 'Europe', 'Southeast Asia', 'United State'];
    return [
        'tour_code' => rand(1, 5) . strtoupper($faker->randomLetter) . strtoupper($faker->randomLetter) . strtoupper($faker->randomLetter),
        'name' => rand(1, 5) . "D" . rand(1, 5) . "N " . $faker->country . " Trip",
        'destination' => $faker->city,
        'category' => $selection[rand(0, 4)],
//        'itinerary_url' => Storage::putFile('public/Tour/itinerary', $faker->image(null, 300, 200), 'public'),
//        'thumbnail_url' => Storage::putFile('public/Tour/thumbnail', $faker->image(null, 300, 200), 'public'),
        'itinerary_url' => "",
        'thumbnail_url' => "",
    ];
});
