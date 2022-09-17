<?php

namespace Database\Seeders;

use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    public function run()
    {
        Feedback::factory()->count(10)->create();
    }
}
