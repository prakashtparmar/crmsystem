<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            // Core
            'dashboard',

            // System
            'users',
            'roles',

            // Commerce
            'customers',
            'orders',
            'order-returns',
            'products',
            'inventory',

            // Masters
            'categories',
            'subcategories',
            'brands',
            'units',
            'crops',
            'seasons',
            'product-variants',
            'product-attributes',
            'product-images',
            'product-tags',
            'batch-lots',
            'expiries',
            'certifications',

            // Marketing
            'coupons',
            'campaigns',

            // Reports
            'reports-sales',
            'reports-customers',
            'reports-performance',
            'reports-conversion',

            // Settings
            'settings-profile',
            'settings-password',
            'settings-appearance',

            // Warehouses
            'warehouses',

            // Finance / Logistics
            'invoices',
            'shipments',
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

        /**
         * Scoped permissions (Own vs All)
         */
        $scopedModules = [
            'orders',
            'customers',
            'campaigns',
            'inventory',
            'reports',
            'invoices',
            'shipments',
            'order-returns',
        ];

        foreach ($scopedModules as $module) {
            Permission::firstOrCreate([
                'name' => "{$module}.view_all",
                'guard_name' => 'web',
            ]);

            Permission::firstOrCreate([
                'name' => "{$module}.view_own",
                'guard_name' => 'web',
            ]);
        }

        // Non-CRUD permissions used in routes
        Permission::firstOrCreate(['name' => 'customers.search', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'cart.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'cart.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'checkout.create', 'guard_name' => 'web']);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
