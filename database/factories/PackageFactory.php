<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Tour;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function rand;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    #[ArrayShape(['price' => "int", 'tour_id' => "int", 'capacity' => "int", 'airline' => "mixed|string", 'depart_time' => "\Carbon\Carbon"])]
    public function definition(): array
    {
        $tourCount = Tour::count();
        return [
            'price' => rand(1000, 2000),
            'tour_id' => rand(1, $tourCount),
            'capacity' => rand(25, 30),
            'depart_time' => Carbon::now()->addDays(rand(30, 180))->addSeconds(rand(7000, rand(0, 100000))),
        ];
    }
}
