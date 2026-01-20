<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $baseSamples = [
            [
                'first_name' => 'Ramesh',
                'last_name' => 'Patel',
                'display_name' => 'Ramesh Patel',
                'type' => 'farmer',
                'category' => 'individual',
                'state' => 'Gujarat',
                'district' => 'Anand',
                'village' => 'Anand',
                'latitude' => 22.5645,
                'longitude' => 72.9289,
                'land_area' => 5.50,
                'primary_crops' => ['Wheat', 'Cotton'],
                'secondary_crops' => ['Mustard'],
                'irrigation_type' => 'canal',
                'credit_limit' => 50000,
            ],
            [
                'first_name' => 'Amit',
                'last_name' => 'Shah',
                'display_name' => 'Amit Shah Traders',
                'type' => 'dealer',
                'category' => 'business',
                'state' => 'Gujarat',
                'district' => 'Rajkot',
                'village' => null,
                'latitude' => 22.3039,
                'longitude' => 70.8022,
                'land_area' => null,
                'primary_crops' => null,
                'secondary_crops' => null,
                'irrigation_type' => null,
                'credit_limit' => 250000,
            ],
            [
                'first_name' => 'Suresh',
                'last_name' => 'Yadav',
                'display_name' => 'Suresh Yadav',
                'type' => 'buyer',
                'category' => 'individual',
                'state' => 'Madhya Pradesh',
                'district' => 'Sehore',
                'village' => 'Sehore',
                'latitude' => 23.2037,
                'longitude' => 77.0844,
                'land_area' => 3.25,
                'primary_crops' => ['Soybean'],
                'secondary_crops' => ['Gram'],
                'irrigation_type' => 'rainfed',
                'credit_limit' => 30000,
            ],
        ];

        $counter = Customer::withTrashed()->max('id') ?? 0;

        for ($i = 1; $i <= 10; $i++) {
            $base = $baseSamples[$i % count($baseSamples)];
            $counter++;

            $customer = Customer::create([
                'uuid'          => (string) Str::uuid(),
                'customer_code' => 'CUST-' . str_pad($counter, 6, '0', STR_PAD_LEFT),

                'first_name'   => $base['first_name'] . $i,
                'last_name'    => $base['last_name'],
                'display_name' => $base['display_name'] . " {$i}",
                'mobile'       => '9' . str_pad((string) rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'email'        => strtolower($base['first_name']) . $i . '@example.com',

                'type'     => $base['type'],
                'category' => $base['category'],

                'company_name' => $base['category'] === 'business'
                    ? $base['display_name'] . " {$i}"
                    : null,

                'gst_number' => $base['category'] === 'business'
                    ? '24ABCDE' . rand(1000, 9999) . 'F1Z' . rand(1, 9)
                    : null,

                'pan_number' => 'ABCDE' . rand(1000, 9999) . 'F',

                // Keep legacy fields populated
                'address_line1' => 'Main Road',
                'address_line2' => null,
                'village'       => $base['village'],
                'taluka'        => $base['district'],
                'district'      => $base['district'],
                'state'         => $base['state'],
                'country'       => 'India',
                'pincode'       => (string) rand(360000, 499999),

                'latitude'  => $base['latitude'],
                'longitude' => $base['longitude'],

                'land_area'       => $base['land_area'],
                'land_unit'       => 'acre',
                'primary_crops'   => $base['primary_crops'],
                'secondary_crops' => $base['secondary_crops'],
                'irrigation_type' => $base['irrigation_type'],

                'credit_limit'        => $base['credit_limit'],
                'outstanding_balance' => rand(0, 20000),
                'credit_valid_till'   => now()->addMonths(rand(3, 12)),

                'aadhaar_last4'   => (string) rand(1000, 9999),
                'kyc_completed'   => (bool) rand(0, 1),
                'kyc_verified_at' => now(),

                'is_active'      => true,
                'is_blacklisted' => false,
                'internal_notes' => 'Seeded sample customer ' . $i,

                'created_by' => 1,
            ]);

            // Billing Address (Default)
            CustomerAddress::create([
                'customer_id'   => $customer->id,
                'type'          => 'billing',
                'is_default'    => true,
                'label'         => 'Primary Billing',
                'contact_name'  => $customer->display_name,
                'contact_phone' => $customer->mobile,
                'address_line1' => 'Billing Street ' . $i,
                'village'       => $base['village'],
                'district'      => $base['district'],
                'state'         => $base['state'],
                'country'       => 'India',
                'pincode'       => (string) rand(360000, 499999),
            ]);

            // Shipping Address (Default)
            CustomerAddress::create([
                'customer_id'   => $customer->id,
                'type'          => 'shipping',
                'is_default'    => true,
                'label'         => 'Primary Shipping',
                'contact_name'  => $customer->display_name,
                'contact_phone' => $customer->mobile,
                'address_line1' => 'Shipping Street ' . $i,
                'village'       => $base['village'],
                'district'      => $base['district'],
                'state'         => $base['state'],
                'country'       => 'India',
                'pincode'       => (string) rand(360000, 499999),
            ]);
        }
    }
}
