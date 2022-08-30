<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Settings\PackageSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    use HasFactory;

    public function run()
    {
        Package::factory()->count(50)->afterCreating(function (Package $package) {
            Flight::inRandomOrder()
                ->take(2)
                ->get()
                ->each(function ($flight, $index) use ( $package) {
                    $package->flight()->attach($flight, ['order' => $index]);
                });
            $setting = app(PackageSetting::class);
            foreach ($setting->default_pricing as $pricing) {
                PackagePricing::factory()->create([
                    'package_id' => $package->id,
                    'name' => $pricing['name'],
                    'available_capacity' =>$pricing['capacity'],
                    'total_capacity' => $pricing['capacity'],
                    'is_active' => $pricing['status'],
                ]);
            }
        })->create();

    }
}
