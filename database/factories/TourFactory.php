<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\ArrayShape;
use function rand;
use function strtoupper;
use function time;

class TourFactory extends Factory
{
    protected $model = Tour::class;

    #[ArrayShape(['tour_code' => "string", 'name' => "string", 'category' => "string", 'nights' => "int", 'days' => "int", 'is_active' => "int"])]
    public function definition(): array
    {
        $country = Country::inRandomOrder()->first();
        $selection = ['Asia', 'Arabic', 'Europe', 'Southeast Asia', 'United State'];
        return [
            'tour_code' => rand(1, 5) . strtoupper($this->faker->randomLetter) . strtoupper($this->faker->randomLetter) . strtoupper($this->faker->randomLetter),
            'name' => rand(1, 5) . "D" . rand(1, 5) . "N " . $country->name . " Package",
            'category' => $selection[rand(0, 4)],
            'nights' => rand(1, 5),
            'days' => rand(1, 5),
            'is_active' => rand(0, 1),
        ];
    }

    public function withFakerItineraryAndThumbnail(): TourFactory
    {
        return $this->afterCreating(function (Tour $tour) {
            $tour->addMedia($this->faker->image())->toMediaCollection('thumbnail');
            $tour->addMedia(UploadedFile::fake()->create(time() . 'document.pdf', 100))->toMediaCollection('itinerary');
        });
    }

    public function withItineraryAndThumbnailBinary(): TourFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'itinerary' => UploadedFile::fake()->create(time() . 'document.pdf', 100),
                'thumbnail' => UploadedFile::fake()->image(time() . 'avatar.jpg', 200, 200),
            ];
        });
    }

}
