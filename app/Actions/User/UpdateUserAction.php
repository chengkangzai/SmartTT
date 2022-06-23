<?php

namespace App\Actions\User;

use App\Models\User;

class UpdateUserAction
{
    public function execute(array $data, User $user): User
    {
        $data = \Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ], customAttributes: [
            'name' => __('Name'),
            'email' => __('Email'),
        ])->validate();

        $user->update($data);

        return $user->refresh();
    }
}
