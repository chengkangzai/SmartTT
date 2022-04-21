<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function join;
use function rand;

class TourDescriptionFactory extends Factory
{
    protected $model = TourDescription::class;

    #[ArrayShape(['place' => "string", 'description' => "string", 'tour_id' => "int"])]
    public function definition(): array
    {
        $maxNumber = Tour::count();
        return [
            'place' => join(" ", $this->faker->words(3)),
            'description' => $this->faker->text,
            'tour_id' => rand(1, $maxNumber)
        ];
    }
}
