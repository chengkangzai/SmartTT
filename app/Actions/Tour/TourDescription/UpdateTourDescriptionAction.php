<?php

namespace App\Actions\Tour\TourDescription;

use App\Models\TourDescription;

class UpdateTourDescriptionAction
{
    public function execute(array $data, TourDescription $tourDescription): TourDescription
    {
        $data = \Validator::make($data, [
            'place' => 'required|string|max:255',
            'description' => 'required|string|',
        ])->validate();

        $tourDescription->update([
            ...$data,
        ]);

        return $tourDescription->refresh();
    }
}
