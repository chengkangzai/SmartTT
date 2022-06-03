<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

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
            ]);

            $tour->addMedia($data['itinerary'])->toMediaCollection('itinerary');

            $tour->addMedia($data['thumbnail'])->withResponsiveImages()->toMediaCollection('thumbnail');

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
