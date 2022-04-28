<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use function collect;
use Illuminate\Support\Facades\Storage;
use function dd;
use function dump;

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
            $tour->countries()->detach($tour->countries->pluck('id')->toArray());
            collect($data['countries'])->each(function ($country, $index) use ($tour) {
                $tour->countries()->attach($country, ['order' => $index]);
            });

            if (isset($data['itinerary'])) {
                $media = $tour->getMedia('itinerary')->first();
                $model_type = $media->model_type;
                $model = $model_type::find($media->model_id);
                $model->deleteMedia($media->id);

                $tour->addMedia($data['itinerary'])->toMediaCollection('itinerary');
            }

            if (isset($data['thumbnail'])) {
                $media = $tour->getMedia('thumbnail')->first();
                $model_type = $media->model_type;
                $model = $model_type::find($media->model_id);
                $model->deleteMedia($media->id);

                $tour->addMedia($data['thumbnail'])->toMediaCollection('thumbnail');
            }

            $tour->update([
                ...$data,
            ]);

            return $tour->refresh();
        });
    }
}
