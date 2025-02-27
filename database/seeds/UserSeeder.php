<?php

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
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin',
                'password' => Hash::make('super1234'),
                'role' => 'super',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
