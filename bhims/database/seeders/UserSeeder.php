<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bakehouse.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'role_id' => 1, // Admin role
        ]);

        // Create a manager
        $manager = User::create([
            'name' => 'Store Manager',
            'email' => 'manager@bakehouse.com',
            'password' => Hash::make('password'),
            'phone' => '1234567891',
            'role_id' => 2, // Manager role
        ]);

        // Create a staff member
        $staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@bakehouse.com',
            'password' => Hash::make('password'),
            'phone' => '1234567892',
            'role_id' => 3, // Staff role
        ]);
    }
}
