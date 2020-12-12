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
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);

        $this->call(CountrySeeder::class);

        $this->call(TourSeeder::class);

        $this->call(AirlineSeeder::class);
        $this->call(FlightSeeder::class);
        $this->call(TripSeeder::class);

    }
}