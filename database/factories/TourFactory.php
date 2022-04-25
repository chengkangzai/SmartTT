<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use function rand;
use function strtoupper;

class TourFactory extends Factory
{
    protected $model = Tour::class;

    #[ArrayShape(['tour_code' => "string", 'name' => "string", 'category' => "string", 'itinerary_url' => "false|string", 'thumbnail_url' => "false|string", 'nights' => "int", 'days' => "int"])]
    public function definition(): array
    {
        $country = Country::inRandomOrder()->first();
        $selection = ['Asia', 'Arabic', 'Europe', 'Southeast Asia', 'United State'];
        return [
            'tour_code' => rand(1, 5) . strtoupper($this->faker->randomLetter) . strtoupper($this->faker->randomLetter) . strtoupper($this->faker->randomLetter),
            'name' => rand(1, 5) . "D" . rand(1, 5) . "N " . $country->name . " Package",
            'category' => $selection[rand(0, 4)],
            'itinerary_url' => '',
            'thumbnail_url' => '',
            'nights' => rand(1, 5),
            'days' => rand(1, 5),
        ];
    }

    public function withFakerItineraryAndThumbnail(): TourFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'itinerary_url' => Storage::putFile('public/Tour/itinerary', $this->faker->image(), 'public'),
                'thumbnail_url' => Storage::putFile('public/Tour/thumbnail', $this->faker->image(), 'public'),
            ];
        });
    }

    public function withItineraryAndThumbnailBinary(): TourFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'itinerary' => UploadedFile::fake()->create('document.pdf', 100),
                'thumbnail' => UploadedFile::fake()->create('thumbnail.jpg', 100),
            ];
        });
    }

}
