<?php

use App\Actions\Booking\Invoice\InvoiceAction;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
use LaravelDaily\Invoices\Classes\InvoiceItem;
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

it('should be able to generate invoice item for each guest', function () {
    Mockery::mock('override', InvoiceAction::class)
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('getItems')
        ->andReturn([
            (new InvoiceItem()),
        ]);
});
