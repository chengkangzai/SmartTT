<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Illuminate\Support\Facades\Storage;

class UpdateTourAction
{
    use ValidateTour;

    /**
     * @throws \Throwable
     */
    public function execute(array $data, Tour $tour): Tour
    {
        $data = $this->validate($data, tour: $tour);

        return \DB::transaction(function () use ($data, $tour) {
            $tour->countries()->sync($data['countries']);

            if (isset($data['itinerary'])) {
                Storage::delete($tour->itinerary_url);
                $data['itinerary_url'] = Storage::putFile('public/Tour/itinerary', $data['itinerary'], 'public');
            }

            if (isset($data['thumbnail'])) {
                Storage::delete($tour->thumbnail_url);
                $data['thumbnail_url'] = Storage::putFile('public/Tour/thumbnail', $data['thumbnail'], 'public');
            }

            $tour->update([
                ...$data,
            ]);

            return $tour->refresh();
        });
    }
}
