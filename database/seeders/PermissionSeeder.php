<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $modules = ['Tour', 'Package', 'Flight', 'User Role', 'Booking', 'User'];
        $cruds = ['Create', 'View', 'Update', 'Delete'];
        $temp = collect([]);
        foreach ($modules as $module) {
            foreach ($cruds as $crud) {
                $temp->push([
                    'name' => $crud . " " . $module,
                    'guard_name' => 'web',
                ]);
            }
        }
        DB::table('permissions')->insert($temp->toArray());
    }
}
