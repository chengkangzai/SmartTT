<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('P@$$w0rd'),
        ]);

        User::factory()->count(9)->create();

        $role = Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());

        $role->users()->attach($user);

        Role::create(['name' => 'Manager'])
            ->syncPermissions([
                'View Tour', 'Create Tour', 'Edit Tour', 'Delete Tour', 'Audit Tour',
                'View Tour Description', 'Create Tour Description', 'Edit Tour Description', 'Delete Tour Description', 'Audit Tour Description',
                'View Package', 'Create Package', 'Edit Package', 'Delete Package', 'Audit Package',
                'View Package Pricing', 'Create Package Pricing', 'Edit Package Pricing', 'Delete Package Pricing', 'Audit Package Pricing',
                'View Flight', 'Create Flight', 'Edit Flight', 'Delete Flight', 'Audit Flight',
                'View Booking', 'Create Booking', 'Edit Booking', 'Delete Booking', 'Audit Booking',
                'View User', 'Create User', 'Edit User', 'Delete User', 'Audit User',
                'Update Setting', 'View Setting'
            ])
            ->users()
            ->attach(User::where('id', ">", 1)->take(3)->get());

        Role::create(['name' => 'Staff'])
            ->syncPermissions([
                'View Tour', 'Create Tour', 'Edit Tour', 'Delete Tour',
                'View Tour Description', 'Create Tour Description', 'Edit Tour Description', 'Delete Tour Description',
                'View Package', 'Create Package', 'Edit Package', 'Delete Package',
                'View Package Pricing', 'Create Package Pricing', 'Edit Package Pricing', 'Delete Package Pricing',
                'View Flight', 'Create Flight', 'Edit Flight', 'Delete Flight',
                'View Booking', 'Create Booking', 'Edit Booking', 'Delete Booking',
                'View User',
            ])
            ->users()
            ->attach(User::where('id', ">", 4)->take(3)->get());

        Role::create(['name' => 'Customer'])
            ->syncPermissions([
                'View Tour', 'View Package', 'View Flight', 'View Booking', 'View User',
                'Create Booking', 'View Booking', 'Edit Booking',
            ])
            ->users()
            ->attach(User::where('id', ">", 7)->take(3)->get());

    }
}
