<?php

use App\Actions\Home\GetHomeDataForStaff;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Tour;
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
use function Pest\Laravel\seed;
use Spatie\Activitylog\Models\Activity;

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

function getLastSevenDayInName(): array
{
    return collect()
        ->times(7)
        ->map(fn ($i) => now()->subDays($i)->getTranslatedDayName())
        ->toArray();
}

function getDataByModelField(string $model, string $field): array
{
    return [
        $model::whereDay($field, '=', now()->subDays(6))->count(),
        $model::whereDay($field, '=', now()->subDays(5))->count(),
        $model::whereDay($field, '=', now()->subDays(4))->count(),
        $model::whereDay($field, '=', now()->subDays(3))->count(),
        $model::whereDay($field, '=', now()->subDays(2))->count(),
        $model::whereDay($field, '=', now()->subDays(1))->count(),
        $model::whereDay($field, '=', now())->count(),
    ];
}

it('should get home data for customer', function () {
    [$userCount, $userData, $bookingCount, $bookingData, $tourCount, $packageCount, $logs] = app(GetHomeDataForStaff::class)->execute();

    $mock = Mockery::mock(GetHomeDataForStaff::class);
    $mock->shouldReceive('execute')
        ->andReturn([$userCount, $userData, $bookingCount, $bookingData, $tourCount, $packageCount, $logs]);


    expect($userCount)->toBe(User::count());
    expect($bookingCount)->toBe(Booking::active()->count());
    expect($tourCount)->toBe(Tour::active()->count());
    expect($packageCount)->toBe(Package::active()->count());
    expect($logs)->toEqual(Activity::where('subject_type', Booking::class)->latest()->paginate(10, ['*'], 'logs'));
    expect($userData)->toEqual([
        'label' => getLastSevenDayInName(),
        'data' => getDataByModelField(User::class, 'created_at'),
    ]);
    expect($bookingData)->toEqual([
        'label' => getLastSevenDayInName(),
        'data' => getDataByModelField(Booking::class, 'created_at'),
    ]);
});
