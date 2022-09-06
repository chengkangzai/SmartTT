<?php

use App\Models\User;
use App\Models\Booking;
use App\Filament\Resources\BookingResource;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Filament\Pages\Actions\DeleteAction;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\get;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
    actingAs(User::factory()->superAdmin()->create());
});

it('should render booking index page', function () {
    get(BookingResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render list booking component ', function () {
    $bookings = Booking::factory()->count(10)->create();

    livewire(BookingResource\Pages\ListBookings ::class)
        ->assertCanSeeTableRecords($bookings);
});

it('should render booking create page', function () {
    get(BookingResource::getUrl('create'))
        ->assertSuccessful();
});

it('should render booking view page', function () {
    get(BookingResource::getUrl('view', [
        'record' => Booking::factory()->create()
    ]))->assertSuccessful();
});

it('should render page to view booking record ', function () {
    $booking = Booking::factory()->create();

    livewire(BookingResource\Pages\ViewBooking::class, [
        'record' => $booking->getKey(),
    ])
        ->assertFormSet([
            'user_id' => $booking->user_id,
            'package_id' => $booking->package_id,
            'total_price' => $booking->total_price / 100,
            'discount' => $booking->discount / 100,
            'adult' => $booking->adult,
            'child' => $booking->child,
        ]);
});

it('should render Relationship Manager', function () {
    $booking = Booking::factory()->create();

    $relationships = BookingResource::getRelations();

    foreach ($relationships as $relationship) {
        livewire($relationship, [
            'ownerRecord' => $booking,
        ])
            ->assertSuccessful();
    }

});
