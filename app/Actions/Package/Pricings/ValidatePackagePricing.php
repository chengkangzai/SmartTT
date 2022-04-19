<?php

namespace App\Actions\Package\Pricings;

trait ValidatePackagePricing
{
    private function validate(array $data): array
    {
        if (! isset($data['is_active'])) {
            $data['is_active'] = false;
        }

        return \Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'total_capacity' => 'required|numeric|min:1',
            'is_active' => 'required|boolean',
        ])
            ->validate();
    }
}
