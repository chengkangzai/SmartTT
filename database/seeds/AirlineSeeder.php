<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airlines = [
            ['name' => 'Air Asia'],
            ['name' => 'Malaysia Airline'],
            ['name' => 'Malindo'],
            ['name' => 'Qatar Airline'],
        ];
        DB::table('Airlines')->insert($airlines);
    }
}

