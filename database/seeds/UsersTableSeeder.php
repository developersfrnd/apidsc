<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
                    'name' => 'Desi Sexi Chat Admin',
                    'role' => 2,
                    'email' => 'admin@yopmail.com',
                    'password' => bcrypt('admin@123'),
                    'status' => 1,
                    'gender' => 1
                ]
            ]);
    }
}