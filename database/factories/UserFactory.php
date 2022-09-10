<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Permission\Models\Role;

class UserFactory extends Factory
{
    #[ArrayShape(['name' => 'string', 'email' => 'string', 'email_verified_at' => "\Illuminate\Support\Carbon", 'password' => 'string', 'remember_token' => 'string', 'created_at' => "\Illuminate\Support\Carbon"])]
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'created_at' => now()->subDays(rand(0, 7)),
        ];
    }

    public function unverified(): UserFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function superAdmin(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(Role::findByName('Super Admin'));
        });
    }

    public function manager(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(Role::findByName('Manager'));
        });
    }

    public function customer(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(Role::findByName('Customer'));
        });
    }

    public function staff(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(Role::findByName('Staff'));
        });
    }
}
