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
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('P@$$w0rd'),
        ]);
        $role = Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());
        $role->users()->attach($superAdmin);

        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@manager.com',
            'password' => Hash::make('P@$$w0rd'),
        ]);
        Role::create(['name' => 'Manager'])
            ->syncPermissions([
                'Access Tour', 'View Tour', 'Create Tour', 'Edit Tour', 'Delete Tour', 'Audit Tour',
                'Access Tour Description', 'View Tour Description', 'Create Tour Description', 'Edit Tour Description', 'Delete Tour Description', 'Audit Tour Description',
                'Access Package', 'View Package', 'Create Package', 'Edit Package', 'Delete Package', 'Audit Package',
                'Access Package Pricing', 'View Package Pricing', 'Create Package Pricing', 'Edit Package Pricing', 'Delete Package Pricing', 'Audit Package Pricing',
                'Access Flight', 'View Flight', 'Create Flight', 'Edit Flight', 'Delete Flight', 'Audit Flight',
                'Access Airport', 'View Airport', 'Create Airport', 'Edit Airport', 'Delete Airport', 'Audit Airport',
                'Access Permission', 'View Permission',
                'Access Booking', 'View Booking', 'Create Booking', 'Edit Booking', 'Delete Booking', 'Audit Booking',
                'Access Payment', 'View Payment', 'Create Payment', 'Edit Payment', 'Delete Payment', 'Audit Payment',
                'Access User', 'View User', 'Create User', 'Edit User', 'Delete User', 'Audit User',
                'Access Feedback', 'View Feedback', 'Create Feedback', 'Edit Feedback', 'Delete Feedback', 'Audit Feedback',
                'View Setting', 'Edit Setting',
                'Change User Role',
            ])
            ->users()
            ->attach($manager);

        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@staff.com',
            'password' => Hash::make('P@$$w0rd'),
        ]);

        Role::create(['name' => 'Staff'])
            ->syncPermissions([
                'Access Tour', 'View Tour', 'Create Tour', 'Edit Tour', 'Delete Tour',
                'Access Tour Description', 'View Tour Description', 'Create Tour Description', 'Edit Tour Description', 'Delete Tour Description',
                'Access Package', 'View Package', 'Create Package', 'Edit Package', 'Delete Package',
                'Access Package Pricing', 'View Package Pricing', 'Create Package Pricing', 'Edit Package Pricing', 'Delete Package Pricing',
                'Access Flight', 'View Flight', 'Create Flight', 'Edit Flight', 'Delete Flight',
                'Access Airport', 'View Airport', 'Create Airport', 'Edit Airport', 'Delete Airport', 'Audit Airport',
                'Access Booking', 'View Booking', 'Create Booking', 'Edit Booking', 'Delete Booking',
                'Access Payment', 'Create Payment', 'Edit Payment', 'Delete Payment',
                'Access Feedback', 'View Feedback', 'Create Feedback', 'Delete Feedback',
                'View User',
                'Connect MS OAuth', 'Sync booking to MS Calendar',
            ])
            ->users()
            ->attach($staff);

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@customer.com',
            'password' => Hash::make('P@$$w0rd'),
        ]);
        Role::create(['name' => 'Customer'])
            ->syncPermissions([
                'Access Tour', 'View Tour', 'View Package', 'View Flight', 'View Booking', 'View User',
                'Access Package', 'View Package',
                'Access Package Pricing',
                'Access Booking', 'Create Booking', 'View Booking', 'Edit Booking',
                'Access Payment', 'View Payment', 'Create Payment',
                'Access Feedback', 'View Feedback', 'Create Feedback', 'Edit Feedback', 'Delete Feedback',
                'Connect MS OAuth', 'Sync booking to MS Calendar',
            ])
            ->users()
            ->attach($customer);
    }
}
