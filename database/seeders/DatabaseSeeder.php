<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('local')) {
            Artisan::call('down');
        }
        $this->call([
            PermissionSeeder::class,
            UserRoleSeeder::class,
            CountrySeeder::class,
            AirlineSeeder::class,
            AirportSeeder::class,
            FlightSeeder::class,
            TourSeeder::class,
            PackageSeeder::class,
            BookingSeeder::class
        ]);
        if (app()->environment('local')) {
            Artisan::call('up');
        }
    }
}
