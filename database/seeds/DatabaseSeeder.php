<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        User::create([
            'name'=>'Ching Cheng Kang',
            'email'=>'pycck@hotmail.com',
            'password'=>Hash::make('P@$$w0rd'),
        ]);
    }
}
