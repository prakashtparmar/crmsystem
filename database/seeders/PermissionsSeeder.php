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
            // System
            'users',
            'roles',

            // Commerce
            'customers',
            'orders',
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
        ];

        // Base CRUD permissions
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
         * Scoped (Own vs All) permissions
         * Only for modules where data ownership matters
         */
        $scopedModules = [
            'orders',
            'customers',
            'campaigns',
            'inventory',
            'reports',
            'invoices',
            'shipments',
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
    }
}
