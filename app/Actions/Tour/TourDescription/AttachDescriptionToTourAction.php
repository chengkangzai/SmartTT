<?php

namespace App\Actions\Tour\TourDescription;

use App\Models\Tour;
use App\Models\TourDescription;

class AttachDescriptionToTourAction
{
    public function execute(array $data, Tour $tour): TourDescription
    {
        $data = \Validator::make($data, [
            'place' => 'required',
            'des' => 'required',
        ], customAttributes: [
            'des' => 'Description',
        ])->validate();

        return $tour->description()->create([
            ...$data,
        ]);
    }
}
