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
        factory(Tour::class, 11)->create();
        factory(TourDescription::class, 55)->create();
    }
}
