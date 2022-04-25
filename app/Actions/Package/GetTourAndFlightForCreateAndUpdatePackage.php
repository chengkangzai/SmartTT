<?php

namespace App\Actions\Package;

use App\Models\Flight;
use App\Models\Tour;

class GetTourAndFlightForCreateAndUpdatePackage
{
    public function execute(): array
    {
        $tours = Tour::select(['id', 'name', 'tour_code'])->get();
        $flights = Flight::with('airline:id,name')
            ->select(['id', 'airline_id', 'departure_date', 'arrival_date'])
            ->where('departure_date', ">=", now())
            ->where('arrival_date', ">=", now())
            ->orderBy('departure_date')
            ->orderBy('arrival_date')
            ->get();

        return [$tours, $flights];
    }
}
