<?php

namespace App\Actions\Package;

trait ValidatePackage
{
    public function validate(array $data, bool $isStore = false): array
    {
        if (! isset($data['is_active'])) {
            $data['is_active'] = false;
        }

        $rules = [];
        if ($isStore) {
            $rules = [
                ...$rules,
                'name' => 'required|array',
                'name.*' => 'required|string|max:255',
                'price' => 'required|array',
                'price.*' => 'required|numeric|max:255',
                'total_capacity' => 'required|array',
                'total_capacity.*' => 'required|numeric|max:255',
            ];

            for ($i = 1; $i < count($data['name']) + 1; $i++) {
                if (isset($data['pricing_is_active_' . $i])) {
                    $data['pricing_is_active_' . $i] = true;
                } else {
                    $data['pricing_is_active_' . $i] = false;
                }
                $rules = [
                    ...$rules,
                    'pricing_is_active_' . $i => 'required|boolean',
                ];
            }
        }

        return \Validator::make(
            $data,
            [
                'tour_id' => 'required|integer|exists:tours,id',
                'depart_time' => 'required|date|after:now',
                'flights' => 'required|array|exists:flights,id',
                ...$rules,
            ],
            [
                'name.*.required' => __('Name of the Pricing :index is required'),
                'name.*.max' => __('Name of the Pricing :index must be less than 255 characters'),
                'price.*.required' => __('Price of the Pricing :index is required'),
                'price.*.max' => __('Price of the Pricing :index must be less than 255 characters'),
                'total_capacity.*.required' => __('Total Capacity of the Pricing :index is required'),
                'total_capacity.*.max' => __('Total Capacity of the Pricing :index must be less than 255 characters'),
            ],
            [
                'tour_id' => __('Tour'),
            ]
        )->validate();
    }
}
