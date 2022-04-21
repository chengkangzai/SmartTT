<?php

namespace Database\Seeders;


use App\Models\Country;
use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run()
    {
        Tour::factory()->count(11)->afterCreating(function (Tour $tour) {
            TourDescription::factory()->count(3)->make([
                'tour_id' => $tour->id,
            ])->map(function ($description,$index) {
                $description->order = $index ;
                return $description;
            })->each->save();
            Country::inRandomOrder()
                ->take(rand(1, 4))
                ->get()
                ->each(function (Country $country, $index) use ($tour) {
                    $tour->countries()->attach($country->id, [
                        'order' => $index,
                    ]);
                });
        })
            ->create();
    }
}
