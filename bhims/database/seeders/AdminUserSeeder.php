<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Change this in production!
                'remember_token' => Str::random(10),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // If the user already existed, update their role to admin
        if ($admin->wasRecentlyCreated === false) {
            $admin->update([
                'role' => 'admin',
                'is_active' => true,
            ]);
        }

        $this->command->info('Admin user created/updated successfully!');
        $this->command->warn('Admin credentials:');
        $this->command->line('Email: admin@example.com');
        $this->command->line('Password: password');
        $this->command->warn('Please change the password after first login!');
    }
}
