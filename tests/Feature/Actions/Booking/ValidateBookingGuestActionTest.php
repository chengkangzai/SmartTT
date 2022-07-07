<?php

use App\Actions\Booking\ValidateBookingGuestAction;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Settings\BookingSetting;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertNotEmpty;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
        CountrySeeder::class,
        TourSeeder::class,
        AirlineSeeder::class,
        AirportSeeder::class,
        FlightSeeder::class,
        PackageSeeder::class,
    ]);
});

it('should validate valid data', function () {
    $package = Package::inRandomOrder()->first();
    assertNotEmpty($package);
    $pricing = PackagePricing::query()
        ->whereBelongsTo($package)
        ->available()
        ->active()
        ->orderBy('price')
        ->get();
    assertNotEmpty($pricing);
    $charge_per_child = app(BookingSetting::class)->charge_per_child;
    $guests = [
        ['name' => 'John Doe', 'pricing' => 0, 'price' => $charge_per_child, 'is_child' => true],
        ['name' => 'John Wick', 'pricing' => $pricing->first()->id, 'price' => $pricing->first()->price, 'is_child' => false],
    ];

    try {
        app(ValidateBookingGuestAction::class)->execute($pricing->toArray(), [
            'guests' => $guests,
        ]);
    } catch (ValidationException $e) {
        assertEmpty($e->validator->errors());
        $this->fail('ValidationException thrown');
    }
});

it('should invalidate invalid data', function ($name, $guests) {
    $package = Package::inRandomOrder()->first();
    $pricing = PackagePricing::query()
        ->whereBelongsTo($package)
        ->available()
        ->active()
        ->orderBy('price')
        ->get();

    try {
        app(ValidateBookingGuestAction::class)->execute($pricing->toArray(), [
            'guests' => $guests,
        ]);
        $this->fail('ValidationException thrown');
    } catch (ValidationException $e) {
        assertNotEmpty($e->validator->errors());
    }
})->with([
    ['guests',
        [['name' => '', 'pricing' => 0, 'price' => 200, 'is_child' => true],],
        [['name' => 'John', 'pricing' => null, 'price' => 200, 'is_child' => true]],
        [['name' => 'John', 'pricing' => 0, 'price' => -1, 'is_child' => true]],
        [['name' => 'Johm', 'pricing' => 0, 'price' => 200, 'is_child' => false]],
        [['name' => 'Johm', 'pricing' => 0, 'price' => 200]],
        [['name' => 'Johm', 'pricing' => 0, 'is_child' => false]],
        [['name' => 'Johm', 'price' => 200, 'is_child' => false]],
        [['pricing' => 0, 'price' => 200, 'is_child' => false]]
    ],
]);

it('should invalidate if the package do not have enough of available', function () {
    $package = Package::factory()->create();
    $pricing = PackagePricing::factory()->create([
        'package_id' => $package->id,
        'available_capacity' => 2,
        'total_capacity' => 2,
    ]);

    $guests = [
        ['name' => 'John Doe', 'pricing' => $pricing->id, 'price' => $pricing->price, 'is_child' => false],
        ['name' => 'John Wick', 'pricing' => $pricing->id, 'price' => $pricing->price, 'is_child' => false],
        ['name' => 'John Wick', 'pricing' => $pricing->id, 'price' => $pricing->price, 'is_child' => false],
    ];

    try {
        app(ValidateBookingGuestAction::class)->execute(PackagePricing::where('id', $pricing->id)->get()->toArray(), [
            'guests' => $guests,
        ]);
        $this->fail('ValidationException thrown');
    } catch (ValidationException $e) {
        assertNotEmpty($e->validator->errors()->get('guests'));
    }
});
