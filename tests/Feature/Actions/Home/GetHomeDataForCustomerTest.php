<?php

use App\Actions\Home\GetHomeDataForCustomer;
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
use Illuminate\Pagination\LengthAwarePaginator;

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

it('should get home data for customer', function () {
    $user = User::first();
    [$bookings, $payments] = app(GetHomeDataForCustomer::class)->execute($user);

    expect($bookings)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($payments)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($bookings)->toEqual($user->bookings()->paginate(10, ['*'], 'bookings'))
        ->and($payments)->toEqual($user->payments()->paginate(10, ['*'], 'payments'));
});
