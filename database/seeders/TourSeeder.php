<?php

namespace Database\Seeders;


use App\Models\Country;
use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tour::factory()->count(11)->afterCreating(function (Tour $tour) {
            TourDescription::factory()->count(3)->create([
                'tour_id' => $tour->id,
            ]);
            $tour->countries()->attach(Country::inRandomOrder()->take(3)->get());
        })
            ->create();
    }
}
