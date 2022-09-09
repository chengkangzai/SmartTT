<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $modules = ['Tour', 'Tour Description', 'Package', 'Package Pricing', 'Flight', 'Role', 'Booking', 'Payment', 'User', 'Permission', 'Airport', 'Airline'];
        $operation = ['Access', 'Create', 'View', 'Edit', 'Delete', 'Audit'];
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
            'name' => 'Edit Setting',
            'guard_name' => 'web',
        ]);

        $temp->push([
            'name' => 'View Setting',
            'guard_name' => 'web',
        ]);

        $temp->push([
            'name' => 'Connect MS OAuth',
            'guard_name' => 'web',
        ]);

        $temp->push([
            'name' => 'Access Report',
            'guard_name' => 'web',
        ]);

        $temp->push([
            'name' => 'Sync booking to MS Calendar',
            'guard_name' => 'web',
        ]);

        $temp->push([
            'name' => 'Change User Role',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert($temp->toArray());
    }
}
