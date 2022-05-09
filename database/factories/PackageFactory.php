<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function rand;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    #[ArrayShape(['tour_id' => "int|mixed", 'depart_time' => "\Carbon\Carbon", 'is_active' => "int"])]
    public function definition(): array
    {
        return [
            'tour_id' => Tour::inRandomOrder()->first()->id,
            'depart_time' => Carbon::now()->addDays(rand(30, 180))->addSeconds(rand(7000, rand(0, 100000))),
            'is_active' => rand(0, 1),
        ];
    }
}
