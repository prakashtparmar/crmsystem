<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $masterRole = Role::firstOrCreate([
            'name' => 'Master Admin',
            'guard_name' => 'web',
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'dipak.patel@krushifly.in'],
            [
                'name' => 'Master Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        if (! $admin->hasRole($masterRole->name)) {
            $admin->assignRole($masterRole);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
