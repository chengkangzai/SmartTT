<?php

use App\Models\Country;
use App\Models\Package;
use App\Models\Settings\TourSetting;
use App\Models\Tour;
use App\Models\User;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
        CountrySeeder::class,
        TourSeeder::class,
    ]);
    $this->actingAs(User::first());
});


it('should return index view', function () {
    $this
        ->get(route('tours.index'))
        ->assertViewIs('smartTT.tour.index')
        ->assertViewHas('tours', Tour::with('countries')->orderByDesc('id')->paginate(10));
});

it('should return create view', function () {
    $this
        ->get(route('tours.create'))
        ->assertViewIs('smartTT.tour.create')
        ->assertViewHas('countries', Country::pluck('name', 'id'))
        ->assertViewHas('setting', app(TourSetting::class));
});

it('should return edit view', function () {
    $this
        ->get(route('tours.edit', Tour::first()))
        ->assertViewIs('smartTT.tour.edit')
        ->assertViewHas('countries', Country::pluck('name', 'id'))
        ->assertViewHas('tour', Tour::first());
});

it('should return audit view', function () {
    $this
        ->get(route('tours.audit', Tour::first()))
        ->assertViewIs('smartTT.tour.audit')
        ->assertViewHas('tour', Tour::first())
        ->assertViewHas('logs');
});

it('should return show view', function () {
    $this
        ->get(route('tours.show', Tour::first()))
        ->assertViewIs('smartTT.tour.show')
        ->assertViewHas([
            'tourDes' => Tour::first()->description()->paginate(9, ['*'], 'tourDes'),
            'packages' => Tour::first()->packages()->paginate(9, ['*'], 'packages'),
        ]);
});
$faker = Faker\Factory::create();

it('should store a tour', function () use ($faker) {
    $tour = Tour::factory()->withItineraryAndThumbnailBinary()->make();
    $tour['des'] = [1 => $faker->word, $faker->word, $faker->word];
    $tour['place'] = [1 => $faker->word, $faker->word, $faker->word];
    $tour['countries'] = Country::inRandomOrder()->take(3)->pluck('id')->toArray();

    $this
        ->post(route('tours.store'), $tour->toArray())
        ->assertRedirect(route('tours.index'))
        ->assertSessionHas('success');

    $newTour = Tour::query()->orderByDesc('id')->first();
    expect($newTour->tour_code)->toBe($tour['tour_code']);
    expect($newTour->name)->toBe($tour['name']);
    expect($newTour->nights)->toBe($tour['nights']);
    expect($newTour->days)->toBe($tour['days']);
});


it('should not store a tour bc w/o other param', function () use ($faker) {
    $tour = Tour::factory()->withItineraryAndThumbnailBinary()->make();

    $this
        ->from(route('tours.create'))
        ->post(route('tours.store'), $tour->toArray())
        ->assertRedirect(route('tours.create'))
        ->assertSessionHasErrors();
});


it('should update a tour', function () {
    $oriTour = Tour::factory()->create();
    assertModelExists($oriTour);

    $mockTour = Tour::factory()->make();
    $mockTour['countries'] = Country::inRandomOrder()->take(3)->pluck('id')->toArray();

    $this
        ->from(route('tours.edit', $oriTour))
        ->put(route('tours.update', $oriTour), $mockTour->toArray())
        ->assertRedirect(route('tours.index'))
        ->assertSessionHas('success');

    $newTour = $oriTour->refresh();
    expect($newTour->tour_code)->toBe($oriTour['tour_code']);
    expect($newTour->name)->toBe($oriTour['name']);
    expect($newTour->nights)->toBe($oriTour['nights']);
    expect($newTour->days)->toBe($oriTour['days']);
});


it('should not update a tour bc w/o other param', function () {
    $oriTour = Tour::factory()->create();
    assertModelExists($oriTour);

    $mockTour = Tour::factory()->make();

    $this
        ->from(route('tours.edit', $oriTour))
        ->put(route('tours.update', $oriTour), $mockTour->toArray())
        ->assertRedirect(route('tours.edit', $oriTour))
        ->assertSessionHasErrors();
});

it('should destroy a tour', function () {
    $tour = Tour::factory()->create();
    assertModelExists($tour);

    $this
        ->delete(route('tours.destroy', $tour))
        ->assertRedirect(route('tours.index'))
        ->assertSessionHas('success');

    assertSoftDeleted($tour);
});

it('should not destroy a tour w/ package', function () {
    $tour = Tour::factory()->create();
    assertModelExists($tour);
    $tour->packages()->save(Package::factory()->make());

    $tour->packages()->each(function ($package) {
        assertModelExists($package);
    });

    $this
        ->from(route('tours.index'))
        ->delete(route('tours.destroy', $tour))
        ->assertRedirect(route('tours.index'))
        ->assertSessionHas('errors');

    assertNotSoftDeleted($tour);
});
