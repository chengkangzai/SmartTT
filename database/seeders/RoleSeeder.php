<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $role = Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());

        $role->users()->attach(User::find(1));

        Role::create(['name' => 'Customer'])
            ->syncPermissions([
                'View Tour', 'View Package', 'View Flight', 'View Booking', 'View User',
                'Create Booking', 'View Booking', 'Update Booking',
            ])
            ->users()->attach(User::where('id', "!=", 1)->get());

    }
}
