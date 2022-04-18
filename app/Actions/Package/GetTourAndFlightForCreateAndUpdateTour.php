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
            ->select(['id', 'airline_id', 'depart_time', 'arrive_time'])
            ->where('depart_time', ">=", now())
            ->where('arrive_time', ">=", now())
            ->orderBy('depart_time')
            ->orderBy('arrive_time')
            ->get()
            ->map(function ($flight) {
                $flight->text = $flight->airline->name . " (" . $flight->depart_time->format('d/m/Y H:i') . ") -> (" . $flight->arrive_time->format('d/m/Y H:i') . ")";

                return $flight;
            });

        return [$tours, $flights];
    }
}
