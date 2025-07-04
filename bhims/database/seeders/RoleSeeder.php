<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web', 'description' => 'Administrator with full access'],
            ['name' => 'manager', 'guard_name' => 'web', 'description' => 'Manager with limited admin access'],
            ['name' => 'staff', 'guard_name' => 'web', 'description' => 'Regular staff member'],
            ['name' => 'baker', 'guard_name' => 'web', 'description' => 'Baking staff'],
            ['name' => 'inventory_manager', 'guard_name' => 'web', 'description' => 'Manages inventory and orders'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
