<?php

namespace App\Actions\Tour;

use App\Models\Tour;
use Illuminate\Support\Facades\Storage;

class DestroyTourAction
{
    /**
     * @throws \Exception
     */
    public function execute(Tour $tour): void
    {
        //make sure the tour do not have package
        if ($tour->packages->count() > 0) {
            throw  new \Exception('This tour has package, you can not delete it, please delete the package first');
        }

        Storage::delete([$tour->itinerary_url, $tour->thumbnail_url]);
        $tour->description()->delete();
        $tour->delete();
    }
}
