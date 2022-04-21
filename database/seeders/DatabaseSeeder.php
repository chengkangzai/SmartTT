<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            UserRoleSeeder::class,
            CountrySeeder::class,
            TourSeeder::class,
            AirlineSeeder::class,
            AirportSeeder::class,
            FlightSeeder::class,
            PackageSeeder::class,
            BookingSeeder::class
        ]);
    }
}
