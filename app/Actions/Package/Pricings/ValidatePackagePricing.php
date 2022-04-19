<?php

namespace App\Actions\Package\Pricings;

trait ValidatePackagePricing
{
    private function validate(array $data): array
    {
        return \Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'total_capacity' => 'required|numeric|min:1',
        ])
            ->validate();
    }
}
