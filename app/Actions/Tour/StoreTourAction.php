<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class StoreTourAction
{
    use ValidateTour;

    /**
     * @throws Throwable
     */
    public function execute(array $data): Tour
    {
        $data = $this->validate($data, isStore: true);

        return DB::transaction(function () use ($data) {
            $tour = Tour::create([
                ...$data,
                'itinerary_url' => Storage::putFile('public/Tour/itinerary', $data['itinerary'], 'public'),
                'thumbnail_url' => Storage::putFile('public/Tour/thumbnail', $data['thumbnail'], 'public'),
            ]);

            $place = $data['place'];
            $des = $data['des'];

            for ($i = 1; $i < count($place) + 1; $i++) {
                $tour->description()->create([
                    'place' => $place[$i],
                    'description' => $des[$i],
                    'tour_id' => $tour->id,
                ]);
            }

            return $tour;
        });
    }
}