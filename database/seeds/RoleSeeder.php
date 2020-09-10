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
        $role = Role::create([
            'name' => 'Super Admin'
        ]);
        $permission = Permission::all();
        $role->givePermissionTo($permission);

        $users = User::find(1);
        $role->users()->attach($users);
    }
}
