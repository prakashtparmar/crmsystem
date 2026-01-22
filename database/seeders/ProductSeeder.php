<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\{
    Category,
    SubCategory,
    Brand,
    Unit,
    Crop,
    Season,
    Product,
    ProductVariant,
    ProductAttribute,
    ProductImage,
    ProductTag,
    BatchLot,
    Expiry,
    Certification,
    Warehouse,
    ProductStock,
    StockMovement
};

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category    = Category::first();
        $subcategory = SubCategory::first();
        $brand       = Brand::first();
        $unit        = Unit::first();
        $crop        = Crop::first();
        $season      = Season::first();

        /*
        |--------------------------------------------------------------------------
        | WAREHOUSES
        |--------------------------------------------------------------------------
        */
        $mainWarehouse = Warehouse::firstOrCreate(
            ['code' => 'WH-MAIN'],
            ['name' => 'Main Warehouse', 'address' => 'Head Office']
        );

        $branchWarehouse = Warehouse::firstOrCreate(
            ['code' => 'WH-BR-01'],
            ['name' => 'Branch Warehouse', 'address' => 'Branch Location']
        );

        /*
        |--------------------------------------------------------------------------
        | PRODUCT MASTER DATA
        |--------------------------------------------------------------------------
        */
        $products = [
            [
                'name'  => 'Wheat Hybrid Seed A1',
                'slug'  => 'wheat-hybrid-seed-a1',
                'sku'   => 'WHT-A1',
                'price' => 1200,
                'gst'   => 5,
                'tags'  => ['Best Seller', 'Govt Approved'],
                'attrs' => ['Germination' => '90%', 'Purity' => '98%'],
            ],
            [
                'name'  => 'Rice Basmati Seed',
                'slug'  => 'rice-basmati-seed',
                'sku'   => 'RICE-BAS',
                'price' => 950,
                'gst'   => 5,
                'tags'  => ['Premium', 'High Yield'],
                'attrs' => ['Germination' => '88%', 'Purity' => '97%'],
            ],
            [
                'name'  => 'Maize Hybrid Seed',
                'slug'  => 'maize-hybrid-seed',
                'sku'   => 'MAIZE-HYB',
                'price' => 780,
                'gst'   => 5,
                'tags'  => ['Fast Growth'],
                'attrs' => ['Germination' => '92%'],
            ],
        ];

        foreach ($products as $row) {

            /*
            |--------------------------------------------------------------------------
            | PRODUCT CORE
            |--------------------------------------------------------------------------
            */
            $product = Product::create([
                'category_id'     => $category->id,
                'subcategory_id'  => $subcategory?->id,
                'brand_id'        => $brand?->id,
                'unit_id'         => $unit->id,
                'crop_id'         => $crop?->id,
                'season_id'       => $season?->id,

                'name'            => $row['name'],
                'slug'            => $row['slug'],
                'sku'             => $row['sku'],
                'short_description' => $row['name'] . ' premium quality product',
                'description'     => 'Enterprise grade agriculture product.',
                'price'           => $row['price'],
                'cost_price'      => $row['price'] * 0.7,
                'hsn_code'        => '1209',
                'gst_percent'     => $row['gst'],
                'is_organic'      => str_contains(strtolower($row['name']), 'organic'),
                'min_order_qty'   => 1,
                'max_order_qty'   => 100,
                'shelf_life_days' => 365,
                'is_active'       => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | DEFAULT VARIANT
            |--------------------------------------------------------------------------
            */
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku'         => $row['sku'] . '-1KG',
                'weight'      => 1,
                'price'       => $row['price'],
                'sale_price'  => $row['price'] - 50,
                'is_default'  => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | ATTRIBUTES
            |--------------------------------------------------------------------------
            */
            foreach ($row['attrs'] as $k => $v) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'name'       => $k,
                    'value'      => $v,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | DEFAULT IMAGE
            |--------------------------------------------------------------------------
            */
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => 'products/agriimage.jpg',
                'is_primary' => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | TAGS
            |--------------------------------------------------------------------------
            */
            foreach ($row['tags'] as $tag) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag'        => $tag,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | BATCH & EXPIRY
            |--------------------------------------------------------------------------
            */
            $batch = BatchLot::create([
                'product_variant_id' => $variant->id,
                'batch_number'       => strtoupper(Str::random(6)),
                'manufactured_at'    => now()->subDays(10),
                'quantity'           => 100,
            ]);

            Expiry::create([
                'batch_lot_id' => $batch->id,
                'expiry_date'  => now()->addYear(),
                'is_expired'   => false,
            ]);

            /*
            |--------------------------------------------------------------------------
            | CERTIFICATION
            |--------------------------------------------------------------------------
            */
            Certification::create([
                'product_id'     => $product->id,
                'type'           => 'Govt Approved',
                'certificate_no' => strtoupper(Str::random(8)),
                'issued_by'      => 'Agri Dept',
                'valid_from'     => now()->subMonth(),
                'valid_to'       => now()->addYear(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | ENTERPRISE INVENTORY INITIALIZATION
            |--------------------------------------------------------------------------
            */
            foreach ([$mainWarehouse, $branchWarehouse] as $warehouse) {
                $openingQty = rand(50, 150);

                ProductStock::create([
                    'product_id'   => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity'     => $openingQty,
                    'reserved_qty' => 0,
                ]);

                StockMovement::create([
                    'product_id'     => $product->id,
                    'warehouse_id'   => $warehouse->id,
                    'type'           => 'in',
                    'quantity'       => $openingQty,
                    'balance_after'  => $openingQty,
                    'reference_type' => 'opening',
                    'reference_id'   => null,
                    'remarks'        => 'Opening stock via seeder',
                ]);
            }
        }
    }
}
