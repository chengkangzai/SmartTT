<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    #[ArrayShape(['tour_id' => 'int|mixed', 'depart_time' => "\Carbon\Carbon", 'is_active' => 'int'])]
    public function definition(): array
    {
        return [
            'tour_id' => Tour::count() > 1 ? Tour::inRandomOrder()->first()->id : Tour::factory(),
            'depart_time' => Carbon::now()->addDays(rand(30, 180))->addSeconds(rand(7000, rand(0, 100000))),
            'is_active' => true,
        ];
    }
}
