<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShippingCompanyPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view shipping companies',
            'create shipping companies',
            'edit shipping companies',
            'delete shipping companies',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::where('name', 'Admin')->where('guard_name', 'web')->first();

        if ($admin) {
            $admin->givePermissionTo($permissions);
        }
    }
}