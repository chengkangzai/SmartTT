<?php

use App\Filament\Resources\PackageResource;
use App\Models\Airline;
use App\Models\Flight;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Filament\Pages\Actions\DeleteAction;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
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

it('should render package index page', function () {
    get(PackageResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render list package component ', function () {
    $packages = Package::factory()->count(10)->create();

    livewire(PackageResource\Pages\ListPackages::class)
        ->assertCanSeeTableRecords($packages);
});

it('should render package create page', function () {
    get(PackageResource::getUrl('create'))
        ->assertSuccessful();
});

it('should create package', function () {
    $package = Package::factory()->make();
    $flight = Flight::factory()->create();
    $pricing = PackagePricing::factory()
        ->count(3)
        ->make();
    livewire(PackageResource\Pages\CreatePackage::class)
        ->fillForm([
            'tour_id' => $package->tour_id,
            'depart_time' => $package->depart_time,
            'flight_id' => $flight->id,
            'is_active' => $package->is_active,
            'packagePricing' => $pricing->map(fn ($item, $key) => [
                'price' => $item->price,
                'name' => $item->name,
                'capacity' => $item->total_capacity,
                'is_active' => $item->is_active,
                'total_capacity' => $item->total_capacity,
                'available_capacity' => $item->available_capacity,
            ])->toArray(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('packages', [
        'tour_id' => $package->tour_id,
        'depart_time' => $package->depart_time,
    ]);

    assertDatabaseHas('flight_package', [
        'package_id' => $package->latest()->first()->id,
        'flight_id' => $flight->id,
    ]);
});

it('can validate input', function () {
    livewire(PackageResource\Pages\CreatePackage::class)
        ->fillForm([
            'tour_id' => null,
            'depart_time' => null,
            'flight_id' => null,
            'airline_id' => null,
            'is_active' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'tour_id',
            'depart_time',
            'flight_id',
            'airline_id',
            'is_active',
        ]);
});

it('should render package view page', function () {
    get(PackageResource::getUrl('view', [
        'record' => Package::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to view package record ', function () {
    $package = Package::factory()
        ->afterCreating(function (Package $package) {
            $package->flight()->attach(Flight::factory()->create());
        })
        ->create();

    livewire(PackageResource\Pages\ViewPackage::class, [
        'record' => $package->getKey(),
    ])
        ->assertFormSet([
            'tour_id' => $package->tour_id,
            'depart_time' => $package->depart_time,
            'flight_id' => $package->flight()->pluck('id')->toArray(),
            'is_active' => $package->is_active,
        ]);
});

it('should render package edit page', function () {
    get(PackageResource::getUrl('edit', [
        'record' => Package::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to show package record in edit view', function () {
    $package = Package::factory()
        ->afterCreating(function (Package $package) {
            $package->flight()->attach(Flight::factory()->create());
        })
        ->create();

    livewire(PackageResource\Pages\EditPackage::class, [
        'record' => $package->getKey(),
    ])
        ->assertFormSet([
            'tour_id' => $package->tour_id,
            'depart_time' => $package->depart_time,
            'flight_id' => $package->flight()->pluck('id')->toArray(),
            'is_active' => $package->is_active,
        ]);
});

it('should edit package', function () {
    $package = Package::factory()
        ->afterCreating(function (Package $package) {
            $package->flight()->attach(Flight::factory()->create());
        })
        ->create();
    $newPackage = Package::factory()->make();
    $airline = Airline::factory()->create();
    $flight = Flight::factory()->create();

    livewire(PackageResource\Pages\EditPackage::class, [
        'record' => $package->getKey(),
    ])
        ->fillForm([
            'tour_id' => $newPackage->tour_id,
            'depart_time' => $newPackage->depart_time,
            'flight_id' => $flight->id,
            'airline_id' => $airline->id,
            'is_active' => $newPackage->is_active,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
    expect($package->refresh())
        ->tour_id->toBe($package->tour_id)
        ->depart_time->toDateTimeString()->toBe($package->depart_time->toDateTimeString())
        ->is_active->toBe($package->is_active);
});

it('should delete package', function () {
    $package = Package::factory()->create();

    livewire(PackageResource\Pages\EditPackage::class, [
        'record' => $package->getKey(),
    ])
        ->callPageAction(DeleteAction::class);

    assertSoftDeleted($package);
});

it('should render Relationship Manager', function () {
    $package = Package::factory()->create();

    $relationships = PackageResource::getRelations();

    foreach ($relationships as $relationship) {
        livewire($relationship, [
            'ownerRecord' => $package,
        ])
            ->assertSuccessful();
    }
});
