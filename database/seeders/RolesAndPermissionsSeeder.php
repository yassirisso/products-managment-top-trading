<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Suppliers
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',

            // Clients
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',

            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
        ]);

        $employee = Role::firstOrCreate([
            'name' => 'Employee',
        ]);

        // Admin gets everything
        $admin->givePermissionTo(Permission::all());

        // Employee permissions
        $employee->givePermissionTo([
            'view products',
            'create products',
            'edit products',

            'view suppliers',

            'view clients',
        ]);
    }
}