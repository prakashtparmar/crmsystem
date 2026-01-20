<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Category, SubCategory, Brand, Unit, Crop, Season};

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        $cat = Category::create([
            'name' => 'Seeds',
            'slug' => 'seeds',
        ]);

        SubCategory::create([
            'category_id' => $cat->id,
            'name'        => 'Hybrid Seeds',
            'slug'        => 'hybrid-seeds',
        ]);

        Brand::insert([
            [
                'name'        => 'AgriGold',
                'slug'        => 'agrigold',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'        => 'GreenGrow',
                'slug'        => 'greengrow',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Unit::insert([
            [
                'name'        => 'Kilogram',
                'slug'        => 'kilogram',
                'symbol'      => 'kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'        => 'Quintal',
                'slug'        => 'quintal',
                'symbol'      => 'qtl',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'        => 'Bag',
                'slug'        => 'bag',
                'symbol'      => 'bag',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Crop::insert([
            [
                'name'        => 'Wheat',
                'slug'        => 'wheat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'        => 'Rice',
                'slug'        => 'rice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seasons table has NO slug column
        Season::insert([
            [
                'name'        => 'Kharif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'        => 'Rabi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
