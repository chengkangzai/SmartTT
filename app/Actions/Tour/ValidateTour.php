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

        $rules = ($isStore) ? [
            'des' => 'required|array',
            'des.*' => 'required|string|max:255',
            'place' => 'required|array',
            'place.*' => 'required|string|max:255',
            'tour_code' => 'required|unique:tours,tour_code',
            'itinerary' => 'required|mimes:pdf|max:2048',
            'thumbnail' => 'required|mimes:jpeg,bmp,png|max:2048',
        ] : [
            'tour_code' => 'required|unique:tours,tour_code,' . $tour?->id,
            'itinerary' => 'nullable|mimes:pdf|max:2048',
            'thumbnail' => 'nullable|mimes:jpeg,bmp,png|max:2048',
        ];

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'countries' => 'required|array|exists:countries,id',
            'countries.*' => 'required|integer',
            'nights' => 'required|integer|min:1',
            'days' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            ...$rules,
        ], [
            'des.*.required' => __('Description :index is required'),
            'place.*.required' => __('Place :index is required'),
        ], [
            'name' => __('Name'),
            'category' => __('Category'),
            'countries' => __('Countries'),
            'nights' => __('Nights'),
            'days' => __('Days'),
            'is_active' => __('Active'),
            'tour_code' => __('Tour Code'),
            'itinerary' => __('Itinerary'),
            'thumbnail' => __('Thumbnail'),
            'des.*' => __('Description'),
            'place.*' => __('Place'),
        ])->validate();
    }
}
