<?php

namespace App\Policies;

use App\Models\PackagePricing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePricingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('Access PackagePricing');
    }

    public function view(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('View PackagePricing');
    }

    public function create(User $user): bool
    {
        return $user->can('Create PackagePricing');
    }

    public function update(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Edit PackagePricing');
    }

    public function delete(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Delete PackagePricing');
    }

    public function restore(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Delete PackagePricing');
    }

    public function forceDelete(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Delete PackagePricing');
    }

    public function audit(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Audit PackagePricing');
    }
}
