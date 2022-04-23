<?php

namespace App\Actions\Tour\TourDescription;

use App\Models\Tour;
use App\Models\TourDescription;

class AttachDescriptionToTourAction
{
    public function execute(array $data, Tour $tour): TourDescription
    {
        $data = \Validator::make($data, [
            'place' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ], customAttributes: [
            'description' => 'Description',
        ])->validate();

        return $tour->description()->create([
            ...$data,
            'order' => $tour->description()->count() + 1,
        ]);
    }
}
