<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());
//        $permission = Permission::all();
//        $role->givePermissionTo($permission);

        $users = User::find(1);
        $role->users()->attach($users);

        $role = Role::create(['name' => 'Customer']);

        $permission = Permission::whereIn('name', [
            'View Tour', 'View Trip', 'View Airline', 'View Booking', 'View User',
            'Create Booking', 'View Booking', 'Update Booking',
        ]);
        $role->givePermissionTo($permission);
    }
}
