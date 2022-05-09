<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Artisan::call('down');
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
        Artisan::call('up');
    }
}
