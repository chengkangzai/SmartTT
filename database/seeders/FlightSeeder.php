<?php

namespace Database\Seeders;


use App\Models\Flight;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $flight = [
//            'depart_time' => Carbon::now(),
//            'arrive_time' => Carbon::tomorrow(),
//            'fee' => rand(100, 1000),
//            'airline_id' => Airline::where('name', 'Malaysia Airlines')->first()->id,
//        ];
//        DB::table('flights')->insert($flight);

        Flight::factory()->count(100)->create();
    }
}
