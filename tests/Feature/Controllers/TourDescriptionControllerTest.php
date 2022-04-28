<?php


use App\Models\Tour;
use App\Models\TourDescription;
use App\Models\User;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        CountrySeeder::class,
        PermissionSeeder::class,
        UserRoleSeeder::class,
        TourSeeder::class,
    ]);
    $this->actingAs(User::first());
});

it('should return edit view', function () {
    $des = TourDescription::inRandomOrder()->first();
    $this
        ->get(route('tourDescriptions.edit', $des))
        ->assertViewIs('smartTT.tourDescription.edit')
        ->assertViewHas('tourDescription', $des);
});

it('should return audit view', function () {
    $this
        ->get(route('tourDescriptions.audit', TourDescription::first()))
        ->assertViewIs('smartTT.tourDescription.audit')
        ->assertViewHas('tourDescription', TourDescription::first())
        ->assertViewHas('logs');
});

it('should update tour description', function () {
    $ori = TourDescription::factory()->create();
    $mock = TourDescription::factory()->make();

    $this->put(route('tourDescriptions.update', $ori), $mock->toArray())
        ->assertRedirect(route('tours.show', $ori->tour))
        ->assertSessionHas('success');

    /** @var TourDescription $updated */
    $updated = TourDescription::orderByDesc('id')->first();
    expect($updated->place)->toBe($mock->place);
    expect($updated->description)->toBe($mock->description);
});

it('should destroy package', function () {
    $des = TourDescription::factory()->create();
    assertModelExists($des);
    $this
        ->from(route('tours.show', $des->tour))
        ->delete(route('tourDescriptions.destroy', $des))
        ->assertRedirect(route('tours.show', $des->tour))
        ->assertSessionHas('success');

    assertSoftDeleted($des);
});

it('should attach a des to tour', function () {
    $tour = Tour::factory()->create();
    $des = TourDescription::factory()->make();

    $this
        ->from(route('tours.show', $tour))
        ->post(route('tourDescriptions.attach', $tour), $des->toArray())
        ->assertRedirect(route('tours.show', $tour))
        ->assertSessionHas('success');

    $tour->refresh()->load('description');
    expect($tour->description->contains($des));
});
