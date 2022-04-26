<?php

use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(DatabaseSeeder::class);
    $this->actingAs(User::first());
});

it('should return edit view', function () {
    $pp = PackagePricing::inRandomOrder()->first();
    $this
        ->get(route('packagePricings.edit', $pp))
        ->assertViewIs('smartTT.packagePricing.edit')
        ->assertViewHas('packagePricing', $pp);
});

it('should update Package Pricing', function () {
    $ori = PackagePricing::factory()->create();
    $mock = PackagePricing::factory()->make();

    $this->put(route('packagePricings.update', $ori), $mock->toArray())
        ->assertRedirect(route('packages.show', $ori->package))
        ->assertSessionHas('success');

    /** @var PackagePricing $updated */
    $updated = PackagePricing::orderByDesc('id')->first();
    expect($updated->name)->toBe($mock->name);
    expect($updated->price)->toBe($mock->price);
    expect($updated->total_capacity)->toBe($mock->total_capacity);
    expect($updated->available_capacity)->toBe($mock->available_capacity);
});

it('should destroy package', function () {
    $pp = PackagePricing::factory()->create();
    assertModelExists($pp);
    $this->delete(route('packagePricings.destroy', $pp))
        ->assertRedirect(route('packages.show', $pp->package))
        ->assertSessionHas('success');

    assertSoftDeleted($pp);
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
