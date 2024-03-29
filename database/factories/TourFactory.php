<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Settings\TourSetting;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class TourFactory extends Factory
{
    protected $model = Tour::class;

    public function definition(): array
    {
        $country = Country::factory()->create();
        $setting = app(TourSetting::class);

        $name = rand(1, 5).'D'.rand(1, 5).'N '.$country->name.' Package';

        return [
            'tour_code' => rand(1, 5).strtoupper($this->faker->randomLetter).strtoupper($this->faker->randomLetter).strtoupper($this->faker->randomLetter),
            'name' => $name,
            'slug' => Str::slug($name),
            'category' => $setting->category[array_rand($setting->category)],
            'nights' => rand(1, 5),
            'days' => rand(1, 5),
            'is_active' => true,
        ];
    }

    public function withFakerItineraryAndThumbnail(): TourFactory
    {
        if (app()->environment('testing')) {
            return $this;
        }

        return $this->afterCreating(function (Tour $tour) {
            $tour->addMedia(UploadedFile::fake()->image('s.png', 640, 480))->toMediaCollection('thumbnail');
            $tour->addMedia(UploadedFile::fake()->create(time().'document.pdf', 100))->toMediaCollection('itinerary');
        });
    }

    public function withItineraryAndThumbnailBinary(): TourFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'itinerary' => UploadedFile::fake()->create(time().'document.pdf', 100),
                'thumbnail' => UploadedFile::fake()->image(time().'avatar.jpg', 200, 200),
            ];
        });
    }
}
