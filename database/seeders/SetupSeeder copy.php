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
            'cart', 'checkout', 'invoices', 'shipments',

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
            'orders', 'order-returns', 'customers', 'campaigns', 'inventory', 'reports', 'invoices', 'shipments',
        ];

        foreach ($scopedModules as $module) {
            Permission::firstOrCreate(['name' => "{$module}.view_all", 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => "{$module}.view_own", 'guard_name' => 'web']);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Create Roles
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $manager    = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $seller     = Role::firstOrCreate(['name' => 'Seller', 'guard_name' => 'web']);
        $support    = Role::firstOrCreate(['name' => 'Support', 'guard_name' => 'web']);
        $customer   = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);

        /*
        |--------------------------------------------------------------------------
        | 3. Assign Permissions to Roles
        |--------------------------------------------------------------------------
        */

        // Super Admin gets everything
        $superAdmin->syncPermissions(Permission::all());

        // Admin (full system but not dangerous settings)
        $admin->syncPermissions(Permission::whereNotIn('name', [
            'settings-appearance.delete',
        ])->get());

        // Manager
        $manager->syncPermissions([
            'orders.view', 'orders.create', 'orders.edit',
            'products.view', 'products.create', 'products.edit',
            'customers.view', 'customers.create', 'customers.edit',
            'inventory.view', 'inventory.edit',
        ]);

        // Seller
        $seller->syncPermissions([
            'orders.view_own',
            'products.view',
            'customers.view',
        ]);

        // Support
        $support->syncPermissions([
            'customers.view',
            'orders.view',
        ]);

        // Customer
        $customer->syncPermissions([
            'orders.view_own',
            'products.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4. Create Users & Assign Roles
        |--------------------------------------------------------------------------
        */

        // Super Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'dipak.patel@krushifly.in'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $adminUser->assignRole($superAdmin);

        // Manager
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Store Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $managerUser->assignRole($manager);

        // Seller
        $sellerUser = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Main Seller',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $sellerUser->assignRole($seller);

        // Support
        $supportUser = User::firstOrCreate(
            ['email' => 'support@example.com'],
            [
                'name' => 'Support Agent',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $supportUser->assignRole($support);

        // Demo Customers
        $customers = [
            ['name' => 'John Customer', 'email' => 'john@example.com'],
            ['name' => 'Emma Buyer', 'email' => 'emma@example.com'],
            ['name' => 'Raj Shopper', 'email' => 'raj@example.com'],
        ];

        foreach ($customers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );

            $user->assignRole($customer);
        }
    }
}
