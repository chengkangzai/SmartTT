<?php

use App\Actions\Package\GetTourAndFlightForCreateAndUpdatePackage;
use App\Models\Flight;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertInstanceOf;

beforeEach(function () {
    seed(DatabaseSeeder::class);
    $this->actingAs(User::first());
});

it('should return index view', function () {
    $this
        ->get(route('packages.index'))
        ->assertViewIs('smartTT.package.index')
        ->assertViewHas('packages', Package::with('tour', 'flight.airline:id,name')->orderByDesc('id')->paginate());
});

it('should return create view', function () {
    [$tours, $flight, $setting, $pricingSetting] = app(GetTourAndFlightForCreateAndUpdatePackage::class)->execute();
    $this
        ->get(route('packages.create'))
        ->assertViewIs('smartTT.package.create')
        ->assertViewHas([
            'tours' => $tours,
            'flights' => $flight,
            'setting' => $setting,
            'pricingSetting' => $pricingSetting,
        ]);
});

it('should return edit view', function () {
    [$tours, $flight] = app(GetTourAndFlightForCreateAndUpdatePackage::class)->execute(false, false);
    $this
        ->get(route('packages.edit', Package::first()))
        ->assertViewIs('smartTT.package.edit')
        ->assertViewHas([
            'package' => Package::first(),
            'tours' => $tours,
            'flights' => $flight,
        ]);
});

it('should return show view', function () {
    $this
        ->get(route('packages.show', Package::first()))
        ->assertViewIs('smartTT.package.show')
        ->assertViewHas('package', Package::first());
});

it('should return audit view', function () {
    $this
        ->get(route('packages.audit', Package::first()))
        ->assertViewIs('smartTT.package.audit')
        ->assertViewHas('package', Package::first())
        ->assertViewHas('logs');
});

$faker = Faker\Factory::create();
it('should store a package', function () use ($faker) {
    $package = Package::factory()->make()->toArray();
    $package['name'] = [1 => $faker->word(), $faker->word(), $faker->word()];
    $package['price'] = [1 => $faker->randomDigit(), $faker->randomDigit(), $faker->randomDigit()];
    $package['total_capacity'] = [1 => $faker->randomDigit(), $faker->randomDigit(), $faker->randomDigit()];
    $package['flights'] = Flight::inRandomOrder()->take(4)->get()->pluck('id')->toArray();
    $this
        ->post(route('packages.store'), $package)
        ->assertRedirect(route('packages.index'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $latestPackage = Package::query()->with('pricings')->orderByDesc('id')->get()->first();

    expect($latestPackage->tour_id)->toBe($package['tour_id']);
    assert(Carbon::make($latestPackage->depart_time)->eq(Carbon::make($package['depart_time'])));

    $latestPackage->pricings->each(function ($pricing) use ($package) {
        assertInstanceOf(PackagePricing::class, $pricing);
        assertModelExists($pricing);
    });
});

it('should not store a package bc w/o other param', function () use ($faker) {
    $package = Package::factory()->make()->toArray();

    $this
        ->from(route('packages.create'))
        ->post(route('packages.store'), $package)
        ->assertSessionHasErrors([
            'name',
            'price',
            'total_capacity',
            'flights',
        ]);
});

it('should update a package', function () use ($faker) {
    $package = Package::factory()->create();
    assertModelExists($package);

    $newPackage = Package::factory()->make()->toArray();
    $newPackage['name'] = [1 => $faker->word(), $faker->word(), $faker->word()];
    $newPackage['price'] = [1 => $faker->randomDigit(), $faker->randomDigit(), $faker->randomDigit()];
    $newPackage['total_capacity'] = [1 => $faker->randomDigit(), $faker->randomDigit(), $faker->randomDigit()];
    $newPackage['flights'] = Flight::inRandomOrder()->take(4)->get()->pluck('id')->toArray();

    $this
        ->put(route('packages.update', $package), $newPackage)
        ->assertRedirect(route('packages.index'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $updatedPackage = Package::query()->with('pricings')->find($package->id);

    expect($updatedPackage->tour_id)->toBe($newPackage['tour_id']);
    assert(Carbon::make($updatedPackage->depart_time)->eq(Carbon::make($newPackage['depart_time'])));

    $updatedPackage->pricings->each(function ($pricing) {
        assertInstanceOf(PackagePricing::class, $pricing);
        assertModelExists($pricing);
    });
});


it('should destroy a package', function () {
    $package = Package::factory()->create();
    assertModelExists($package);

    $this
        ->delete(route('packages.destroy', $package))
        ->assertRedirect(route('packages.index'))
        ->assertSessionHas('success');

    assertSoftDeleted($package);
});
