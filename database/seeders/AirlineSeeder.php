<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirlineSeeder extends Seeder
{
    public function run()
    {
        $my = Country::whereName('Malaysia')->first()->id;
        $qatar = Country::whereName('Qatar')->first()->id;
        $airlines = [
            ['name' => 'Air Asia', 'country_id' => $my, 'ICAO' => 'AK', 'IATA' => 'AXM'],
            ['name' => 'Malaysia Airline', 'country_id' => $my, 'ICAO' => 'MH', 'IATA' => 'MAS'],
            ['name' => 'Malindo', 'country_id' => $my, 'ICAO' => 'OD', 'IATA' => 'MXD'],
            ['name' => 'Qatar Airline', 'country_id' => $qatar, 'ICAO' => 'QR', 'IATA' => 'QTR'],
        ];
        DB::table('airlines')->insert($airlines);
    }
}
