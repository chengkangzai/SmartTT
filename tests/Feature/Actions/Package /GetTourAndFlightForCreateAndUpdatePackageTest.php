<?php

use App\Actions\Package\GetTourAndFlightForCreateAndUpdatePackage;
use App\Models\Flight;
use App\Models\Tour;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\TourSeeder;
use Illuminate\Support\Collection;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNull;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(AirportSeeder::class);
    seed(AirlineSeeder::class);
    seed(FlightSeeder::class);
    seed(TourSeeder::class);
});


it('should return tour and flight for create and update package', function () {

    /**
     * @var Collection $tour
     * @var Collection $flight
     */
    [$tour, $flight] = app(GetTourAndFlightForCreateAndUpdatePackage::class)->execute();

    assertNotEmpty($tour);
    assertNotEmpty($flight);
    assertInstanceOf(Collection::class, $tour);
    assertInstanceOf(Collection::class, $flight);
    assertCount(Tour::count(), $tour);

    $tour->each(function (Tour $tour) {
        assertNotEmpty($tour->id);
        assertNotEmpty($tour->name);
        assertNotEmpty($tour->tour_code);
        assertNull($tour->category);
        assertNull($tour->days);
        assertNull($tour->nights);
    });

    $flight->each(function (Flight $flight) {
        assertNotEmpty($flight->id);
        assertNotEmpty($flight->airline_id);
        assertNotEmpty($flight->departure_date);
        assertNotEmpty($flight->arrival_date);
        assertNull($flight->departure_airport_id);
        assertNull($flight->arrival_airport_id);
        assertEmpty($flight->price);
    });
});
