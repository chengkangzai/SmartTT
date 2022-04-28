<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;
use function dd;

class StoreTourAction
{
    use ValidateTour;

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function execute(array $data): Tour
    {
        $data = $this->validate($data, isStore: true);

        return DB::transaction(function () use ($data) {
            $tour = Tour::create([
                ...$data,
                'itinerary_url' => '',
                'thumbnail_url' => '',
            ]);

            $tour->addMedia($data['itinerary'])->toMediaCollection('itinerary');

            $tour->addMedia($data['thumbnail'])->toMediaCollection('thumbnail');

            $place = $data['place'];
            $des = $data['des'];

            for ($i = 1; $i < count($place) + 1; $i++) {
                $tour->description()->create([
                    'place' => $place[$i],
                    'description' => $des[$i],
                    'order' => $i,
                ]);
            }

            collect($data['countries'])
                ->each(function ($country, $index) use ($tour) {
                    $tour->countries()->attach($country, ['order' => $index]);
                });

            return $tour->refresh();
        });
    }
}
