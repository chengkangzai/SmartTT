<?php

namespace App\Policies;

use App\Models\Airport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AirportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Airport');
    }

    public function view(User $user, Airport $airport): bool
    {
        return $user->can('View Airport');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Airport');
    }

    public function update(User $user, Airport $airport): bool
    {
        return $user->can('Edit Airport');
    }

    public function delete(User $user, Airport $airport): bool
    {
        return $user->can('Delete Airport');
    }

    public function restore(User $user, Airport $airport): bool
    {
        return $user->can('Delete Airport');
    }

    public function forceDelete(User $user, Airport $airport): bool
    {
        return $user->can('Delete Airport');
    }

    public function audit(User $user, Airport $airport): bool
    {
        return $user->can('Audit Airport');
    }
}
