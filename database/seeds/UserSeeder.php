<?php

use App\User;
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

        User::create([
            'name' => 'Fake admin',
            'email' => 'fake@hotmail.com',
            'password' => Hash::make('P@$$w0rd'),
        ]);
    }
}
