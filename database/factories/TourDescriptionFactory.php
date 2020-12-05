<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Database\Eloquent\Factories\Factory;
use function join;
use function rand;

class TourDescriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TourDescription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $maxNumber = Tour::count();
        return [
            'place' => join(" ",$this->faker->words(3)),
            'description' => $this->faker->text,
            'tour_id' => rand(1, $maxNumber)
        ];
    }
}
