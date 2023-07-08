<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('local')) {
            Artisan::call('down');
        }
        Model::unguard();
        DB::beginTransaction();
        $this->call([
            PermissionSeeder::class,
            UserRoleSeeder::class,
            CountrySeeder::class,
            AirlineSeeder::class,
            AirportSeeder::class,
            FlightSeeder::class,
            TourSeeder::class,
            PackageSeeder::class,
            BookingSeeder::class,

            FeedbackSeeder::class,
        ]);
        DB::commit();
        Model::reguard();
        if (app()->environment('local')) {
            Artisan::call('up');
        }
    }
}
