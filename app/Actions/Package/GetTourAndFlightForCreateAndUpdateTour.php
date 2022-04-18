<?php

namespace App\Actions\Package;

use App\Models\Flight;
use App\Models\Tour;

class GetTourAndFlightForCreateAndUpdateTour
{
    public function execute()
    {
        $tours = Tour::select(['id', 'name'])->get();
        $flights = Flight::with('airline:id,name')
            ->select(['id', 'airline_id', 'departure_date', 'arrival_date'])
            ->where('departure_date', ">=", now())
            ->where('arrival_date', ">=", now())
            ->orderBy('departure_date')
            ->orderBy('arrival_date')
            ->get()
            ->map(function (Flight $flight) {
                $flight->text = $flight->airline->name . " (" . $flight->departure_date->format('d/m/Y H:i') . ") -> (" . $flight->arrival_date->format('d/m/Y H:i') . ")";

                return $flight;
            });

        return [$tours, $flights];
    }
}
