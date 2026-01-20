<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $adminRole   = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $sellerRole  = Role::firstOrCreate(['name' => 'seller']);
        $supportRole = Role::firstOrCreate(['name' => 'support']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $admin->assignRole($adminRole);

        // Store Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Store Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $manager->assignRole($managerRole);

        // Seller / Vendor
        $seller = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Main Seller',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $seller->assignRole($sellerRole);

        // Support Staff
        $support = User::firstOrCreate(
            ['email' => 'support@example.com'],
            [
                'name' => 'Support Agent',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
        $support->assignRole($supportRole);

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

            $user->assignRole($customerRole);
        }
    }
}
