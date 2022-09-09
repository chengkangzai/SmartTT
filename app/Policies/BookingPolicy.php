<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Booking');
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->can('View Booking');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Booking');
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->can('Edit Booking');
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->can('Delete Booking');
    }

    public function restore(User $user, Booking $booking): bool
    {
        return $user->can('Delete Booking');
    }

    public function forceDelete(User $user, Booking $booking): bool
    {
        return $user->can('Delete Booking');
    }

    public function audit(User $user, Booking $booking): bool
    {
        return $user->can('Audit Booking');
    }
}
