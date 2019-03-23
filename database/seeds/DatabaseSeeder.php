<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
    }
}

class UsersTableSeeder extends Seeder {

    public function run()
    {
        \DB::table('users')->delete();

        \App\User::create([
            'name' => 'Administrator',
            'email' => 'admin@myproducts.com',
            'password' => Hash::make('admin123')
        ]);
    }
}
