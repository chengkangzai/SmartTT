<?php

use App\Actions\Package\StorePackageAction;
use App\Models\Flight;
use App\Models\Package;
use App\Models\PackagePricing;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\TourSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertInstanceOf;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
    seed(AirlineSeeder::class);
    seed(AirportSeeder::class);
    seed(FlightSeeder::class);
});

$faker = Faker\Factory::create();

it('should store package', function () use ($faker) {
    $mockPackage = Package::factory()->make()->toArray();
    $mockPackage['name'] = [1 => $faker->word(), $faker->word(), $faker->word()];
    $mockPackage['price'] = [1 => $faker->randomDigit(), $faker->randomDigit(), $faker->randomDigit()];
    $mockPackage['total_capacity'] = [1 => $faker->randomDigit(), $faker->randomDigit(), $faker->randomDigit()];
    $mockPackage['flights'] = Flight::inRandomOrder()->take(4)->get()->pluck('id')->toArray();

    $action = app(StorePackageAction::class);

    try {
        $package = $action->execute($mockPackage);
    } catch (Throwable $e) {
        $this->fail($e->getMessage());
    }

    expect($package)->toBeInstanceOf(Package::class);
    assertModelExists($package);

    $package->refresh()->pricings()->get()->each(function ($pricing) use ($mockPackage) {
        assertInstanceOf(PackagePricing::class, $pricing);
        assertModelExists($pricing);
    });
});
