<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateProfileAction
{
    public function execute(array $data, User $user): User
    {
        $data = \Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed|min:8',
        ],customAttributes: [
            'name' => __('Name'),
            'email' => __('Email'),
            'password' => __('Password'),
        ])->validate();

        if (isset($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $user->update([
            ...$data,
        ]);

        return $user->refresh();
    }
}
