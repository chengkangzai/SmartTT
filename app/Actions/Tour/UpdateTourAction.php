<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

class UpdateTourAction
{
    use ValidateTour;

    /**
     * @throws Throwable
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function execute(array $data, Tour $tour): Tour
    {
        $data = $this->validate($data, tour: $tour);

        return \DB::transaction(function () use ($data, $tour) {
            $tour->countries()->detach($tour->countries->pluck('id')->toArray());
            collect($data['countries'])->each(function ($country, $index) use ($tour) {
                $tour->countries()->attach($country, ['order' => $index]);
            });

            if (isset($data['itinerary'])) {
                $this->updateFile($tour, 'itinerary', $data);
            }

            if (isset($data['thumbnail'])) {
                $this->updateFile($tour, 'thumbnail', $data);
            }

            $tour->update([
                ...$data,
            ]);

            return $tour->refresh();
        });
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function updateFile(Tour $tour, string $mode, $data): void
    {
        $media = $tour->getMedia($mode)->first();
        if ($media) {
            $model_type = $media->model_type;
            $model = $model_type::find($media->model_id);
            $model->deleteMedia($media->id);
            if ($mode == 'thumbnail') {
                $tour->addMedia($data[$mode])
                    ->withResponsiveImages()
                    ->toMediaCollection($mode);
            } else {
                $tour->addMedia($data[$mode])
                    ->toMediaCollection($mode);
            }
        }
    }
}
