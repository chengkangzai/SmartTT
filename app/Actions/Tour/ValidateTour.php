<?php

namespace App\Actions\Tour;

trait ValidateTour
{
    public function validate(array $data, bool $isStore = false): array
    {
        $rules = ($isStore) ?
            [
                'des' => 'required|array|min:1|max:5',
                'des.*' => 'required|string',
                'place' => 'required|array|min:1|max:5',
                'place.*' => 'required|string',
                'tour_code' => 'required|unique:tours,tour_code',
            ] :
            [
                'tour_code' => 'required|unique:tours,tour_code,' . $data['tour_code'],
            ];

        return \Validator::make(
            $data,
            [
                'name' => 'required',
                'destination' => 'required',
                'category' => 'required',
                'itinerary' => 'required|mimes:pdf|max:2048',
                'thumbnail' => 'required|mimes:jpeg,bmp,png|max:2048',
                +$rules,
            ],
            [
                'des.*.required' => 'Description :index is required',
                'place.*.required' => 'Place :index is required',
            ]
        )
            ->validate();
    }
}
