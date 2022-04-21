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
            'name' => 'Ching Cheng Kang',
            'email' => 'pycck@hotmail.com',
            'password' => Hash::make('P@$$w0rd'),
        ]);

        User::factory()->count(9)->create();

        $role = Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());

        $role->users()->attach($user);

        Role::create(['name' => 'Manager'])
            ->syncPermissions([
                'View Tour', 'Create Tour', 'Update Tour', 'Delete Tour',
                'View Package', 'Create Package', 'Update Package', 'Delete Package',
                'View Flight', 'Create Flight', 'Update Flight', 'Delete Flight',
                'View Booking', 'Create Booking', 'Update Booking', 'Delete Booking',
                'View User', 'Create User', 'Update User', 'Delete User',
            ])
            ->users()
            ->attach(User::where('id', ">", 1)->take(3)->get());

        Role::create(['name' => 'Staff'])
            ->syncPermissions([
                'View Tour', 'Create Tour', 'Update Tour', 'Delete Tour',
                'View Package', 'Create Package', 'Update Package', 'Delete Package',
                'View Flight', 'Create Flight', 'Update Flight', 'Delete Flight',
                'View Booking', 'Create Booking', 'Update Booking', 'Delete Booking',
                'View User',
            ])
            ->users()
            ->attach(User::where('id', ">", 4)->take(3)->get());

        Role::create(['name' => 'Customer'])
            ->syncPermissions([
                'View Tour', 'View Package', 'View Flight', 'View Booking', 'View User',
                'Create Booking', 'View Booking', 'Update Booking',
            ])
            ->users()
            ->attach(User::where('id', ">", 7)->take(3)->get());

    }
}
