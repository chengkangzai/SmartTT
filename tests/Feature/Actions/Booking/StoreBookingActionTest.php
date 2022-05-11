<?php

use App\Actions\Booking\StoreBookingAction;
use App\Models\BookingGuest;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\User;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

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
        BookingSeeder::class
    ]);
});

it('should create a booking', function () {
    $user = User::factory()->create();
    $package = Package::factory()->create();
    $pricing = PackagePricing::factory(3)->create([
        'package_id' => $package->id,
    ]);
    $guests = BookingGuest::factory(rand(2, 5))->create([
        'package_pricing_id' => $pricing->random()->id,
    ]);
    $booking = app(StoreBookingAction::class)->execute($user, [
        'package_id' => $package->id,
        'adult' => $guests->where('is_child', false)->count(),
        'child' => $guests->where('is_child', true)->count(),
        'total_price' => 500,
        'guests' => $guests->map(function ($guest) {
            return [
                'name' => $guest->name,
                'price' => $guest->packagePricing->price ?? 200,
                'is_child' => $guest->is_child,
                'pricing' => $guest->package_pricing_id,
            ];
        })->toArray(),
    ]);

    assertModelExists($booking);
    expect($booking->id)->toBeGreaterThan(0);
    expect($booking->package_id)->toBe($package->id);
    expect($booking->adult)->toBe($guests->where('is_child', false)->count());
    expect($booking->child)->toBe($guests->where('is_child', true)->count());
    expect($booking->total_price)->toBe(500);
    expect($booking->guests->count())->toBe($guests->count());
    expect($booking->guests->pluck('name')->toArray())->toBe($guests->pluck('name')->toArray());
    expect($booking->guests->pluck('price')->toArray())->toBe($guests->pluck('price')->toArray());
    expect($booking->guests->pluck('package_pricing_id')->toArray())->toBe($guests->pluck('package_pricing_id')->toArray());
    expect($booking->user_id)->toBe($user->id);
    expect($booking->discount)->toBe(0);
});
