<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access Payment');
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->can('View Payment');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Payment');
    }

    public function update(User $user, Payment $payment): bool
    {
        return $user->can('Edit Payment');
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->can('Delete Payment');
    }

    public function restore(User $user, Payment $payment): bool
    {
        return $user->can('Delete Payment');
    }

    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->can('Delete Payment');
    }

    public function audit(User $user, Payment $payment): bool
    {
        return $user->can('Audit Payment');
    }
}
