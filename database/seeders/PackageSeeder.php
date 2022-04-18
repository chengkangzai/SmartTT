<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    use HasFactory;

    public function run()
    {
        Package::factory()->count(10)->afterCreating(function (Package $package) {
            Flight::inRandomOrder()
                ->take(2)
                ->get()
                ->each(function ($flight, $index) use ( $package) {
                    $package->flight()->attach($flight, ['order' => $index]);
                });
        })->create();

    }
}
