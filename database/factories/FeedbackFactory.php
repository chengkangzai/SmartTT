<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->text,
            'name' => $this->faker->name,
            'is_listed' => $this->faker->boolean,
            'user_id' => User::count() ? User::inRandomOrder()->first()->id : User::factory(),
        ];
    }
}
