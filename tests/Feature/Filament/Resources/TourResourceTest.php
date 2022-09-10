<?php

use App\Filament\Resources\TourResource;
use App\Models\Country;
use App\Models\Package;
use App\Models\Tour;
use App\Models\TourDescription;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Filament\Pages\Actions\DeleteAction;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
    actingAs(User::factory()->superAdmin()->create());
});

it('should render tour index page', function () {
    get(TourResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render list tour component ', function () {
    $tours = Tour::factory()->count(10)->create();

    livewire(TourResource\Pages\ListTours::class)
        ->assertCanSeeTableRecords($tours);
});

it('should render tour create page', function () {
    get(TourResource::getUrl('create'))
        ->assertSuccessful();
});

it('should create tour', function () {
    $tour = Tour::factory()->make();
    $countries = Country::factory()->count(2)->create();
    livewire(TourResource\Pages\CreateTour::class)
        ->fillForm([
            'name' => $tour->name,
            'tour_code' => $tour->tour_code,
            'category' => $tour->category,
            'countries' => $countries->pluck('id')->toArray(),
            'days' => $tour->days,
            'nights' => $tour->nights,
            'is_active' => $tour->is_active,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'itinerary',
            'thumbnail',
        ]);
});

it('can validate input', function () {
    livewire(TourResource\Pages\CreateTour::class)
        ->fillForm([
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name',
            'tour_code',
            'category',
            'countries',
            'days',
            'nights',
            'is_active',
            'itinerary',
            'thumbnail',
        ]);
});

it('should render tour view page', function () {
    get(TourResource::getUrl('view', [
        'record' => Tour::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to view tour record ', function () {
    $tour = Tour::factory()->create();

    livewire(TourResource\Pages\ViewTour::class, [
        'record' => $tour->getKey(),
    ])
        ->assertFormSet([
            'name' => $tour->name,
            'tour_code' => $tour->tour_code,
            'category' => $tour->category,
            'countries' => $tour->countries->pluck('id')->toArray(),
            'days' => $tour->days,
            'nights' => $tour->nights,
            'is_active' => $tour->is_active,
        ]);
});

it('should render tour edit page', function () {
    get(TourResource::getUrl('edit', [
        'record' => Tour::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to show tour record in edit view', function () {
    $tour = Tour::factory()->create();

    livewire(TourResource\Pages\EditTour::class, [
        'record' => $tour->getKey(),
    ])
        ->assertFormSet([
            'name' => $tour->name,
            'tour_code' => $tour->tour_code,
            'category' => $tour->category,
            'countries' => $tour->countries->pluck('id')->toArray(),
            'days' => $tour->days,
            'nights' => $tour->nights,
            'is_active' => $tour->is_active,
        ]);
});

it('should edit tour', function () {
    $tour = Tour::factory()->create();
    $newTour = Tour::factory()->make();

    livewire(TourResource\Pages\EditTour::class, [
        'record' => $tour->getKey(),
    ])
        ->fillForm([
            'name' => $newTour->name,
            'tour_code' => $newTour->tour_code,
            'category' => $newTour->category,
            'days' => $newTour->days,
            'nights' => $newTour->nights,
            'is_active' => $newTour->is_active,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'countries',
            'itinerary',
            'thumbnail',
        ]);
});

it('should delete tour', function () {
    $tour = Tour::factory()->create();

    livewire(TourResource\Pages\EditTour::class, [
        'record' => $tour->getKey(),
    ])
        ->callPageAction(DeleteAction::class);

    assertSoftDeleted($tour);
});

it('should render Description Relationship Manager', function () {
    $tour = Tour::factory()
        ->hasDescription(3, TourDescription::factory()->make()->toArray())
        ->has(Package::factory()->count(2))
        ->create();

    $relationships = TourResource::getRelations();

    foreach ($relationships as $relationship) {
        livewire($relationship, [
            'ownerRecord' => $tour,
        ])
            ->assertSuccessful();
    }
});
