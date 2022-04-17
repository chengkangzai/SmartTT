<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use function rand;
use function strtoupper;

class TourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['tour_code' => "string", 'name' => "string", 'destination' => "string", 'category' => "string", 'itinerary_url' => "false|string", 'thumbnail_url' => "false|string", 'country_id' => "mixed"])]
    public function definition(): array
    {
        $selection = ['Asia', 'Arabic', 'Europe', 'Southeast Asia', 'United State'];
        $country = \DB::table('countries')->inRandomOrder()->take(1)->first();
        return [
            'tour_code' => rand(1, 5) . strtoupper($this->faker->randomLetter) . strtoupper($this->faker->randomLetter) . strtoupper($this->faker->randomLetter),
            'name' => rand(1, 5) . "D" . rand(1, 5) . "N " . $country->name . " Trip",
            'destination' => $this->faker->city,
            'category' => $selection[rand(0, 4)],
            'itinerary_url' => Storage::putFile('public/Tour/itinerary', $this->faker->image(), 'public'),
            'thumbnail_url' => Storage::putFile('public/Tour/thumbnail', $this->faker->image(), 'public'),
            'country_id' => $country->id,
        ];
    }
}
