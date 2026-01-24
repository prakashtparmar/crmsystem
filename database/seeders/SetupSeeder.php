<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SetupSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | 1. Create Permissions
        |--------------------------------------------------------------------------
        */

        $modules = [
            // System
            'users', 'roles', 'dashboard',

            // E-Commerce / Commerce
            'customers', 'orders', 'order-returns', 'products', 'inventory',
            'cart', 'checkout', 'invoices', 'shipments','warehouses',

            // Masters
            'categories', 'subcategories', 'brands', 'units', 'crops', 'seasons',
            'product-variants', 'product-attributes', 'product-images', 'product-tags',
            'batch-lots', 'expiries', 'certifications',

            // Marketing
            'coupons', 'campaigns',

            // Reports
            'reports-sales', 'reports-customers', 'reports-performance', 'reports-conversion',

            // Settings
            'settings-profile', 'settings-password', 'settings-appearance',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Scoped permissions
        $scopedModules = [
            'orders', 'order-returns', 'customers', 'campaigns',
            'inventory', 'reports', 'invoices', 'shipments',
        ];

        foreach ($scopedModules as $module) {
            Permission::firstOrCreate(['name' => "{$module}.view_all", 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => "{$module}.view_own", 'guard_name' => 'web']);
        }

        // Extra granular permissions
        Permission::firstOrCreate(['name' => 'customers.search', 'guard_name' => 'web']);

        /*
        |--------------------------------------------------------------------------
        | 2. Create Super Admin Role
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. Assign ALL Permissions to Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(Permission::all());

        /*
        |--------------------------------------------------------------------------
        | 4. Create Super Admin User & Assign Role
        |--------------------------------------------------------------------------
        */

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        $adminUser->assignRole($superAdmin);
    }
}
