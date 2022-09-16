<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Role;

class RegisterNewUserAsCustomer
{
    public function handle(Registered $event)
    {
        /** @var User $user */
        $user = $event->user;
        $user->assignRole(Role::findByName('Customer'));
    }
}
