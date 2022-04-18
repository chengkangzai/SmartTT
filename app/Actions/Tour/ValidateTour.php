<?php

namespace App\Actions\Tour;

use App\Models\Tour;

trait ValidateTour
{
    public function validate(array $data, bool $isStore = false, Tour $tour = null): array
    {
        $rules = ($isStore) ?
            [
                'des' => 'required|array',
                'des.*' => 'required|string',
                'place' => 'required|array',
                'place.*' => 'required|string',
                'tour_code' => 'required|unique:tours,tour_code',
                'itinerary' => 'required|mimes:pdf|max:2048',
                'thumbnail' => 'required|mimes:jpeg,bmp,png|max:2048',
            ] :
            [
                'tour_code' => 'required|unique:tours,tour_code,' . $tour->id,
            ];

        return \Validator::make(
            $data,
            [
                'name' => 'required',
                'category' => 'required',
                'countries' => 'required|array|exists:countries,id',
                'nights' => 'required|integer|min:1',
                'days' => 'required|integer|min:1',
                ...$rules,
            ],
            [
                'des.*.required' => 'Description :index is required',
                'place.*.required' => 'Place :index is required',
            ]
        )
            ->validate();
    }
}
