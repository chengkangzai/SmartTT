<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirlineSeeder extends Seeder
{
    public function run()
    {
        $country = DB::table('countries')->get();
        $my = $country->where('name', 'Malaysia')->first()->id;
        $qatar = $country->where('name', 'Qatar')->first()->id;
        $airlines = [
            ['name' => 'Air Asia', 'country_id' => $my, 'ICAO' => 'AK', 'IATA' => 'AXM'],
            ['name' => 'Malaysia Airline', 'country_id' => $my, 'ICAO' => 'MH', 'IATA' => 'MAS'],
            ['name' => 'Malindo', 'country_id' => $my, 'ICAO' => 'OD', 'IATA' => 'MXD'],
            ['name' => 'Qatar Airline', 'country_id' => $qatar, 'ICAO' => 'QR', 'IATA' => 'QTR'],
        ];
        DB::table('airlines')->insert($airlines);
    }
}

