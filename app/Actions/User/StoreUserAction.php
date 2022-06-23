<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StoreUserAction
{
    public function execute(array $data): User
    {
        $data = \Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ],customAttributes: [
            'name' => __('Name'),
            'email' => __('Email'),
            'password' => __('Password'),
        ])->validate();

        $user = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->sync(Role::findByName('Customer'));

        return $user->refresh();
    }
}
