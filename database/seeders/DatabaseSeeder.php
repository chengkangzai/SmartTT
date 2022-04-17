<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            CountrySeeder::class,
            TourSeeder::class,
            AirlineSeeder::class,
            AirportSeeder::class,
            FlightSeeder::class,
            PackageSeeder::class,
        ]);
    }
}
