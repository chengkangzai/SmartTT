<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function join;

class TourDescriptionFactory extends Factory
{
    protected $model = TourDescription::class;

    #[ArrayShape(['place' => "string", 'description' => "string", 'tour_id' => "int", 'order' => "int"])]
    public function definition(): array
    {
        $tour = Tour::inRandomOrder()->first();

        return [
            'place' => join(" ", $this->faker->words(3)),
            'description' => $this->faker->text,
            'tour_id' => $tour->id,
            'order' => TourDescription::whereBelongsTo($tour)->count() + 1,
        ];
    }
}
