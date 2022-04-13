<?php

namespace App\Actions\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateProfileAction
{
    public function execute(array $data): bool
    {
        $data = \Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255', Rule::unique('users')->ignore(Auth::user())],
            'password' => ['nullable', 'string', 'confirmed', 'min:8'],
        ])->validate();

        if (isset($data['password'])) {
            auth()->user()->update(['password' => Hash::make($data['password'])]);
        }

        return auth()->user()->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }
}
