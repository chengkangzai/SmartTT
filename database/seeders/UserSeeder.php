<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Ching Cheng Kang',
            'email' => 'pycck@hotmail.com',
            'password' => Hash::make('P@$$w0rd'),
        ]);

        User::factory()->count(10)->create();
    }
}
