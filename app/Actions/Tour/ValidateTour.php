<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Validator;

trait ValidateTour
{
    public function validate(array $data, bool $isStore = false, Tour $tour = null): array
    {
        if (! isset($data['is_active'])) {
            $data['is_active'] = false;
        }

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

        return Validator::make(
            $data,
            [
                'name' => 'required',
                'category' => 'required',
                'countries' => 'required|array|exists:countries,id',
                'nights' => 'required|integer|min:1',
                'days' => 'required|integer|min:1',
                'is_active' => 'required|boolean',
                ...$rules,
            ],
            [
                'des.*.required' => 'Description :index is required',
                'place.*.required' => 'Place :index is required',
            ]
        ) ->validate();
    }
}
