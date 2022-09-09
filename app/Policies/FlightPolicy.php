<?php

namespace App\Policies;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlightPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Flight');
    }

    public function view(User $user, Flight $flight): bool
    {
        return $user->can('View Flight');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Flight');
    }

    public function update(User $user, Flight $flight): bool
    {
        return $user->can('Edit Flight');
    }

    public function delete(User $user, Flight $flight): bool
    {
        return $user->can('Delete Flight');
    }

    public function restore(User $user, Flight $flight): bool
    {
        return $user->can('Delete Flight');
    }

    public function forceDelete(User $user, Flight $flight): bool
    {
        return $user->can('Delete Flight');
    }

    public function audit(User $user, Flight $flight): bool
    {
        return $user->can('Audit Flight');
    }
}
