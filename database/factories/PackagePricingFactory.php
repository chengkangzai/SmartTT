<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PackagePricing;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class PackagePricingFactory extends Factory
{
    protected $model = PackagePricing::class;

    #[ArrayShape(['package_id' => 'int|mixed', 'price' => 'int', 'name' => 'array|string', 'total_capacity' => 'int', 'available_capacity' => 'int', 'is_active' => 'int'])]
    public function definition(): array
    {
        return [
            'package_id' => Package::count() > 1 ? Package::inRandomOrder()->first()->id : Package::factory(),
            'price' => rand(500, 5000),
            'name' => $this->faker->words(rand(1, 3), true),
            'total_capacity' => 10,
            'available_capacity' => 10,
            'is_active' => true,
        ];
    }
}
