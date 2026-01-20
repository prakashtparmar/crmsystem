<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            PermissionsSeeder::class,
        RolesSeeder::class,
        MasterSeeder::class,
        ProductSeeder::class,
        ComplianceSeeder::class,
        CustomerSeeder::class,

        ]);
    }
}
