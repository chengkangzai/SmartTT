<?php

use App\Airline;
use App\Flight;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

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
        factory(Flight::class, 100)->create();
    }
}
