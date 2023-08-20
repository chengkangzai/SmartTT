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
        BookingSeeder::class,
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
    expect($booking->id)->toBeGreaterThan(0)
        ->and($booking->package_id)->toBe($package->id)
        ->and($booking->adult)->toBe($guests->where('is_child', false)->count())
        ->and($booking->child)->toBe($guests->where('is_child', true)->count())
        ->and($booking->total_price)->toBe(500)
        ->and($booking->guests->count())->toBe($guests->count())
        ->and($booking->guests->pluck('name')->toArray())->toBe($guests->pluck('name')->toArray())
        ->and($booking->guests->pluck('price')->toArray())->toBe($guests->pluck('price')->toArray())
        ->and($booking->guests->pluck('package_pricing_id')->toArray())->toBe($guests->pluck('package_pricing_id')->toArray())
        ->and($booking->user_id)->toBe($user->id)
        ->and($booking->discount)->toBe(0);
});
