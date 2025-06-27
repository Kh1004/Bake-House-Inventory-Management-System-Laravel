<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bakehouse.com',
            'password' => Hash::make('password'),
            'phone' => '+1 (555) 123-4567',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create managers
        $managers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@bakehouse.com',
                'phone' => '+1 (555) 234-5678',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@bakehouse.com',
                'phone' => '+1 (555) 345-6789',
            ]
        ];

        foreach ($managers as $manager) {
            User::create([
                'name' => $manager['name'],
                'email' => $manager['email'],
                'password' => Hash::make('password'),
                'phone' => $manager['phone'],
                'role' => 'manager',
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        // Create staff members
        $staffMembers = [
            [
                'name' => 'Emma Wilson',
                'email' => 'emma.wilson@bakehouse.com',
                'phone' => '+1 (555) 456-7890',
            ],
            [
                'name' => 'James Rodriguez',
                'email' => 'james.rodriguez@bakehouse.com',
                'phone' => '+1 (555) 567-8901',
            ],
            [
                'name' => 'Priya Patel',
                'email' => 'priya.patel@bakehouse.com',
                'phone' => '+1 (555) 678-9012',
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.kim@bakehouse.com',
                'phone' => '+1 (555) 789-0123',
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@bakehouse.com',
                'phone' => '+1 (555) 890-1234',
            ]
        ];

        foreach ($staffMembers as $staff) {
            User::create([
                'name' => $staff['name'],
                'email' => $staff['email'],
                'password' => Hash::make('password'),
                'phone' => $staff['phone'],
                'role' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create some inactive accounts
        $inactiveUsers = [
            [
                'name' => 'Inactive Staff',
                'email' => 'inactive.staff@bakehouse.com',
                'phone' => '+1 (555) 111-2233',
                'role' => 'staff',
            ],
            [
                'name' => 'Old Manager',
                'email' => 'old.manager@bakehouse.com',
                'phone' => '+1 (555) 222-3344',
                'role' => 'manager',
            ]
        ];

        foreach ($inactiveUsers as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'phone' => $user['phone'],
                'role' => $user['role'],
                'is_active' => false,
                'email_verified_at' => now(),
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(2),
            ]);
        }
    }
}
