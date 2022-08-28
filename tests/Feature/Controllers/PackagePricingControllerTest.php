<?php

use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\User;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
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
    ]);
    $this->actingAs(User::first());
});

it('should return edit view', function () {
    $pp = PackagePricing::inRandomOrder()->first();
    $this
        ->get(route('packagePricings.edit', $pp))
        ->assertViewIs('smartTT.packagePricing.edit')
        ->assertViewHas('packagePricing', $pp);
});

it('should return audit view', function () {
    $this
        ->get(route('packagePricings.audit', PackagePricing::first()))
        ->assertViewIs('smartTT.packagePricing.audit')
        ->assertViewHas('packagePricing', PackagePricing::first())
        ->assertViewHas('logs');
});

it('should update Package Pricing', function () {
    $ori = PackagePricing::factory()->create();
    $mock = PackagePricing::factory()->make();

    $this->put(route('packagePricings.update', $ori), $mock->toArray())
        ->assertRedirect(route('packages.show', $ori->package))
        ->assertSessionHas('success');

    $updated = PackagePricing::query()->orderByDesc('id')->first();
    expect($updated->name)->toBe($mock->name)
        ->and($updated->price)->toBe($mock->price)
        ->and($updated->total_capacity)->toBe($mock->total_capacity)
        ->and($updated->available_capacity)->toBe($mock->available_capacity);
});

it('should destroy package', function () {
    $pp = PackagePricing::factory()->create();
    assertModelExists($pp);
    $this->delete(route('packagePricings.destroy', $pp))
        ->assertRedirect(route('packages.show', $pp->package))
        ->assertSessionHas('success');

    assertSoftDeleted($pp);
});


it('should not destroy package because it been used', function () {
    $package = Package::factory()->create();
    $pp = PackagePricing::factory()->create([
        'package_id' => $package->id,
    ]);
    assertModelExists($pp);
    Booking::factory()->create();
    $pp->guests()->createMany(BookingGuest::factory()->times(3)->make()->toArray());

    $this->from(route('packages.show', $package))
        ->delete(route('packagePricings.destroy', $pp))
        ->assertRedirect(route('packages.show', $package))
        ->assertSessionHasErrors();

    assertModelExists($pp);
});

it('should attach a pricing to package', function () {
    $package = Package::factory()->create();
    $pricing = PackagePricing::factory()->make();

    $this
        ->from(route('packages.show', $package))
        ->post(route('packagePricings.attach', $package), $pricing->toArray())
        ->assertRedirect(route('packages.show', $package))
        ->assertSessionHas('success');

    $package->refresh()->load('pricings');
    expect($package->pricings->contains($pricing));
});
