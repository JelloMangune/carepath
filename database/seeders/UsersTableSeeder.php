<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create an Admin user
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'user_type' => 0, // 0 for admin
                'barangay_id' => null, // Admin doesn't belong to any barangay, so set it to null
            ],
        ]);

        // Create Health Care Workers for each barangay
        $barangays = DB::table('barangays')->get();

        foreach ($barangays as $barangay) {
            DB::table('users')->insert([
                [
                    'name' => 'Health Care Worker ' . $barangay->name,
                    'username' => 'worker_' . $barangay->name,
                    'password' => Hash::make('password'),
                    'user_type' => 1, // 1 for health care worker
                    'barangay_id' => $barangay->id,
                ],
            ]);
        }
    }
}
