<?php

namespace App\Policies;

use App\Models\Airline;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AirlinePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Airline');
    }

    public function view(User $user, Airline $airline): bool
    {
        return $user->can('View Airline');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Airline');
    }

    public function update(User $user, Airline $airline): bool
    {
        return $user->can('Edit Airline');
    }

    public function delete(User $user, Airline $airline): bool
    {
        return $user->can('Delete Airline');
    }

    public function restore(User $user, Airline $airline): bool
    {
        return $user->can('Delete Airline');
    }

    public function forceDelete(User $user, Airline $airline): bool
    {
        return $user->can('Delete Airline');
    }

    public function audit(User $user, Airline $airline): bool
    {
        return $user->can('Audit Airline');
    }
}
