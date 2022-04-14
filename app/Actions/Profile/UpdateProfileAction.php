<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateProfileAction
{
    public function execute(array $data, User $user): bool
    {
        $data = \Validator::make($data, [
            'name' => ['required|string|max:255'],
            'email' => ['required|email|string|max:255|unique:users,email,' . $user->id],
            'password' => ['nullable|string|confirmed|min:8'],
        ])->validate();

        if (isset($data['password'])) {
            auth()->user()->update(['password' => Hash::make($data['password'])]);
        }

        return auth()->user()->update([
            ...$data
        ]);
    }
}
