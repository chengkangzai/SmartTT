<?php

namespace App\Actions\Setting\Edit;

use App\Models\Tour;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class GetViewBagForTourSettingAction implements GetViewBagSettingInterface
{
    #[ArrayShape(['tours' => "mixed"])]
    public function execute(): array
    {
        return [
            'tours' => $this->calcCategoryUsedInTour(),
        ];
    }

    private function calcCategoryUsedInTour(): array
    {
        return Tour::get(['category'])
            ->groupBy('category')
            ->map(function (Collection $item) {
                return $item->count();
            })
            ->toArray();
    }
}
