<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = ['Tour', 'Trip', 'Flight', 'User Role', 'Booking', 'User'];
        $cruds = ['Create', 'View', 'Update', 'Delete'];
        $temp = collect([]);
        foreach ($modules as $module) {
            foreach ($cruds as $crud) {
                $temp->push([
                    'name' => $crud . " " . $module,
                    'module' => $module,
                    'guard_name' => 'web',
                ]);
            }
        }
        DB::table('permissions')->insert($temp->toArray());
    }
}
