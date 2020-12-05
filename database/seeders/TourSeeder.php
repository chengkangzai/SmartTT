<?php

namespace Database\Seeders;


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
        Tour::factory()->count(11)->create();
        TourDescription::factory()->count(11)->create();
    }
}
