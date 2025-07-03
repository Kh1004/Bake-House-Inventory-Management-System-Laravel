<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator with full access'],
            ['name' => 'manager', 'description' => 'Manager with limited admin access'],
            ['name' => 'staff', 'description' => 'Regular staff member'],
            ['name' => 'baker', 'description' => 'Baking staff'],
            ['name' => 'inventory_manager', 'description' => 'Manages inventory and orders'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
