<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->delete();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@myproducts.com',
            'password' => Hash::make('admin123')
        ]);
    }
}
