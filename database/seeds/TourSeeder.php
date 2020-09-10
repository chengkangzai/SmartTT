<?php

use App\Tour;
use App\TourDescription;
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
        $tour =Tour::create([
            'tour_code' => "Test",
            'name' => "Test",
            'destination' => "Test",
            'category' => "Test",
            'itinerary_url' => "Lol",
            'thumbnail_url' => "Lol",
        ]);
        $temp = [
            [
                'place'=>'Random place la',
                'description'=>'Where you want to go',
                'tour_id'=>$tour->id,
            ],
            [
                'place'=>'Random place la',
                'description'=>'Where you want to go',
                'tour_id'=>$tour->id,
            ],
            [
                'place'=>'Random place la',
                'description'=>'Where you want to go',
                'tour_id'=>$tour->id,
            ]
        ];
        TourDescription::insert($temp);
    }
}
