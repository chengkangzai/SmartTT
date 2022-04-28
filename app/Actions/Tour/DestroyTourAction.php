<?php

namespace App\Actions\Tour;

use App\Models\Tour;

class DestroyTourAction
{
    /**
     * @throws \Exception
     */
    public function execute(Tour $tour): void
    {
        if ($tour->packages->count() > 0) {
            throw  new \Exception('This tour has package, you can not delete it, please delete the package first');
        }

        $this->deleteFile($tour, 'itinerary');
        $this->deleteFile($tour, 'thumbnail');

        $tour->description()->delete();
        $tour->delete();
    }

    private function deleteFile(Tour $tour, string $mode): void
    {
        $medias = $tour->getMedia($mode)->all();

        foreach ($medias as $media) {
            $model_type = $media->model_type;
            $model = $model_type::find($media->model_id);
            $model->deleteMedia($media->id);
        }
    }
}
