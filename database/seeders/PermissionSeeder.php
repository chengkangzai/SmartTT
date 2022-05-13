<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $modules = ['Tour', 'Tour Description', 'Package', 'Package Pricing', 'Flight', 'Role', 'Booking', 'Payment', 'User'];
        $operation = ['Create', 'View', 'Edit', 'Delete', 'Audit'];
        $temp = collect([]);
        foreach ($modules as $module) {
            foreach ($operation as $crud) {
                $temp->push([
                    'name' => $crud . " " . $module,
                    'guard_name' => 'web',
                ]);
            }
        }

        $temp->push([
            'name' => 'Update Setting',
            'guard_name' => 'web',
        ]);

        $temp->push([
            'name' => 'View Setting',
            'guard_name' => 'web',
        ]);


        DB::table('permissions')->insert($temp->toArray());
    }
}
