<?php

namespace App\Actions\Reports\ViewBag;

use App\Models\Tour;
use JetBrains\PhpStorm\ArrayShape;

class GetViewBagForSalesReportAction
{
    #[ArrayShape(['categories' => "array"])]
    public function execute(): array
    {
        return [
            'categories' => Tour::select('category')
                ->distinct()
                ->pluck('category')
                ->toArray(),
        ];
    }
}