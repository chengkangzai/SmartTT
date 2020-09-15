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
        factory(Tour::class, 10)->create();
        factory(TourDescription::class, 50)->create();
    }
}
