<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    use HasFactory;

    public function run()
    {
        Package::factory()->count(10)->create();
    }
}
