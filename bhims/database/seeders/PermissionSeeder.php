<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Roles
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Permissions
            'view permissions',
            'edit permissions',
            
            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Inventory
            'view inventory',
            'manage inventory',
            
            // Orders
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            
            // Reports
            'view reports',
            'generate reports',
            
            // Settings
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign all permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->givePermissionTo(Permission::all());

        // Assign permissions to manager role
        $managerRole = Role::where('name', 'manager')->first();
        $managerPermissions = [
            'view users',
            'create users',
            'edit users',
            'view products',
            'create products',
            'edit products',
            'view inventory',
            'manage inventory',
            'view orders',
            'create orders',
            'edit orders',
            'view reports',
            'generate reports',
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // Assign permissions to staff role
        $staffRole = Role::where('name', 'staff')->first();
        $staffPermissions = [
            'view products',
            'view inventory',
            'view orders',
            'create orders',
        ];
        $staffRole->givePermissionTo($staffPermissions);


    }
}
