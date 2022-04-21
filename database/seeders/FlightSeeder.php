<?php

namespace Database\Seeders;


use App\Models\Flight;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    public function run()
    {
        Flight::factory()->count(100)->create();
    }
}
