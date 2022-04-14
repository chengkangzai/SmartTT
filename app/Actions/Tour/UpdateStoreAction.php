<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Illuminate\Support\Facades\Storage;

class UpdateStoreAction
{
    use ValidateTour;

    public function execute(array $data, Tour $tour): Tour
    {
        $data = $this->validate($data);

        if (isset($data['itinerary'])) {
            Storage::delete($tour->itinerary_url);
        }
        if (isset($data['thumbnail'])) {
            Storage::delete($tour->thumbnail_url);
        }

        $tour->update([
            ...$data,
            'itinerary_url' => Storage::putFile('public/Tour/itinerary', $data['itinerary'], 'public'),
            'thumbnail_url' => Storage::putFile('public/Tour/thumbnail', $data['thumbnail'], 'public'),
        ]);

        return $tour->refresh();
    }
}
