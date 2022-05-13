<?php

namespace App\Actions\Home;

use App\Models\User;

class GetHomeDataForCustomer
{
    public function execute(User $user): array
    {
        return [
            $user->bookings()->paginate(10, ['*'], 'bookings'),
            $user->payments()->paginate(10, ['*'], 'payments'),
        ];
    }
}
