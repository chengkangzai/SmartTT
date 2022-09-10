<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class TourDescriptionFactory extends Factory
{
    protected $model = TourDescription::class;

    #[ArrayShape(['place' => 'string', 'description' => 'string', 'tour_id' => 'int', 'order' => 'int'])]
    public function definition(): array
    {
        return [
            'place' => implode(' ', $this->faker->words(3)),
            'description' => $this->faker->text,
            'tour_id' => Tour::factory(),
            'order' => rand(1, 10),
        ];
    }
}
