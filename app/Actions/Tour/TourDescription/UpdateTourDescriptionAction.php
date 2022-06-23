<?php

namespace App\Actions\Tour\TourDescription;

use App\Models\TourDescription;

class UpdateTourDescriptionAction
{
    public function execute(array $data, TourDescription $tourDescription): TourDescription
    {
        $data = \Validator::make($data, [
            'place' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ],customAttributes: [
            'description' => __('Description'),
            'place' => __('Place'),
        ])->validate();

        $tourDescription->update([
            ...$data,
        ]);

        return $tourDescription->refresh();
    }
}
