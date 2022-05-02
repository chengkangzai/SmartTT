<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Settings\PackagePricingsSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;
use function app;

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
            $setting = app(PackagePricingsSetting::class);
            foreach ($setting->default_namings as $key => $name) {
                PackagePricing::factory()->create([
                    'package_id' => $package->id,
                    'name' => $name,
                    'available_capacity' => $setting->default_capacity[$key],
                    'total_capacity' => $setting->default_capacity[$key],
                    'is_active' => $setting->default_status[$key],
                ]);
            }
        })->create();

    }
}
