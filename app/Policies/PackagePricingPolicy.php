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
        return $user->can('Access Package Pricing');
    }

    public function view(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('View Package Pricing');
    }

    public function create(User $user): bool
    {
        return $user->can('Create Package Pricing');
    }

    public function update(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Edit Package Pricing');
    }

    public function delete(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Delete Package Pricing');
    }

    public function restore(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Delete Package Pricing');
    }

    public function forceDelete(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Delete Package Pricing');
    }

    public function audit(User $user, PackagePricing $packagePricing): bool
    {
        return $user->can('Audit Package Pricing');
    }
}
