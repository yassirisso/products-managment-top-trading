<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'products',
            'suppliers',
            'clients',
            'packing lists',
            'proforma invoices',
            'commercial invoices',
            'bank accounts',
            'users',
        ];

        Permission::firstOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']);

        foreach ($modules as $module) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                Permission::firstOrCreate([
                    'name'       => "{$action} {$module}",
                    'guard_name' => 'web',
                ]);
            }
        }

        Permission::firstOrCreate(['name' => 'import products', 'guard_name' => 'web']);

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions(
            Permission::whereIn('name', $this->namesFor(['view', 'create', 'edit'], $modules))->get()
        );

        $employee = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $employee->syncPermissions(
            Permission::whereIn('name', [
                'view dashboard',
                'view products',
                'create products',
                'edit products',
                'view suppliers',
                'create suppliers',
                'view clients',
                'create clients',
                'view packing lists',
                'create packing lists',
                'view proforma invoices',
                'view commercial invoices',
                'view bank accounts',
            ])->get()
        );

        $viewer = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions(
            Permission::where('name', 'like', 'view %')->get()
        );

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function namesFor(array $actions, array $modules): array
    {
        $names = [];
        foreach ($actions as $action) {
            foreach ($modules as $module) {
                $names[] = "{$action} {$module}";
            }
        }
        return $names;
    }
}
