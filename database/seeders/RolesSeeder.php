<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $user = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
        ]);

        // Super Admin gets everything
        $superAdmin->syncPermissions(Permission::all());

        // Admin permissions
        $admin->syncPermissions([
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            'roles.view',
            'roles.create',
            'roles.edit',

            'orders.view',
            'products.view',
            'categories.view',
            'customers.view',
        ]);

        // Normal User permissions
        $user->syncPermissions([
            'users.view',
        ]);
    }
}
