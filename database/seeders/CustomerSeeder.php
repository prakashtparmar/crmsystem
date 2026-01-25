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
        $samples = [
            [
                'first_name' => 'Ramesh',
                'middle_name' => 'Bhai',
                'last_name' => 'Patel',
                'type' => 'farmer',
                'category' => 'individual',
                'state' => 'Gujarat',
                'district' => 'Anand',
                'village' => 'Anand',
                'taluka' => 'Anand',
                'region' => 'Central Gujarat',
                'primary_crops' => ['Wheat', 'Cotton'],
                'secondary_crops' => ['Mustard'],
                'irrigation_type' => 'canal',
                'land_area' => 6.25,
            ],
            [
                'first_name' => 'Amit',
                'middle_name' => 'Kumar',
                'last_name' => 'Shah',
                'type' => 'dealer',
                'category' => 'business',
                'state' => 'Gujarat',
                'district' => 'Rajkot',
                'village' => 'Rajkot',
                'taluka' => 'Rajkot',
                'region' => 'Saurashtra',
                'primary_crops' => null,
                'secondary_crops' => null,
                'irrigation_type' => null,
                'land_area' => null,
            ],
            [
                'first_name' => 'Suresh',
                'middle_name' => 'Ram',
                'last_name' => 'Yadav',
                'type' => 'buyer',
                'category' => 'individual',
                'state' => 'Madhya Pradesh',
                'district' => 'Sehore',
                'village' => 'Sehore',
                'taluka' => 'Sehore',
                'region' => 'Malwa',
                'primary_crops' => ['Soybean'],
                'secondary_crops' => ['Gram'],
                'irrigation_type' => 'rainfed',
                'land_area' => 3.50,
            ],
        ];

        $counter = Customer::withTrashed()->max('id') ?? 0;

        for ($i = 1; $i <= 15; $i++) {
            $base = $samples[$i % count($samples)];
            $counter++;

            $display = "{$base['first_name']} {$base['last_name']} {$i}";

            $customer = Customer::create([
                'uuid'          => (string) Str::uuid(),
                'customer_code' => 'CUST-' . str_pad($counter, 6, '0', STR_PAD_LEFT),

                'first_name'   => $base['first_name'],
                'middle_name'  => $base['middle_name'],
                'last_name'    => $base['last_name'],
                'display_name' => $display,
                'mobile'       => '9' . str_pad((string) rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'email'        => strtolower($base['first_name']) . $i . '@example.com',

                'phone_number_2' => '9' . rand(100000000, 999999999),
                'relative_phone' => '9' . rand(100000000, 999999999),

                'type'     => $base['type'],
                'category' => $base['category'],

                'company_name' => $base['category'] === 'business' ? "{$display} Traders" : null,
                'gst_number'   => $base['category'] === 'business' ? '24ABCDE' . rand(1000, 9999) . 'F1Z' . rand(1, 9) : null,
                'pan_number'   => 'ABCDE' . rand(1000, 9999) . 'F',
                'business_type' => $base['category'] === 'business' ? 'wholesaler' : null,
                'registration_number' => $base['category'] === 'business' ? 'REG' . rand(10000, 99999) : null,
                'website' => $base['category'] === 'business' ? 'https://example' . $i . '.com' : null,
                'established_year' => $base['category'] === 'business' ? rand(2005, 2020) : null,
                'annual_turnover' => $base['category'] === 'business' ? rand(500000, 5000000) : null,

                'address_line1' => 'Main Road',
                'address_line2' => 'Near Market',
                'village'       => $base['village'],
                'taluka'        => $base['taluka'],
                'district'      => $base['district'],
                'state'         => $base['state'],
                'country'       => 'India',
                'pincode'       => (string) rand(360000, 499999),
                'post_office'   => $base['village'] . ' PO',

                'land_area'       => $base['land_area'],
                'land_unit'       => 'acre',
                'primary_crops'   => $base['primary_crops'],
                'secondary_crops' => $base['secondary_crops'],
                'irrigation_type' => $base['irrigation_type'],
                'farming_method'  => $base['type'] === 'farmer' ? 'conventional' : null,
                'soil_type'       => $base['type'] === 'farmer' ? 'Black' : null,

                'credit_limit'        => rand(20000, 300000),
                'outstanding_balance' => rand(0, 20000),
                'credit_valid_till'   => now()->addMonths(rand(3, 12)),
                'payment_terms'       => rand(15, 60),
                'preferred_payment_mode' => ['cash','upi','bank','credit'][rand(0,3)],
                'bank_name'           => 'State Bank of India',
                'account_number'      => (string) rand(100000000000, 999999999999),
                'ifsc_code'           => 'SBIN000' . rand(100, 999),
                'billing_cycle'       => ['weekly','fortnightly','monthly'][rand(0,2)],

                'referred_by' => 'Field Agent',
                'reference_type' => 'dealer',
                'reference_name' => 'Local Dealer',
                'reference_phone' => '9' . rand(100000000, 999999999),
                'secondary_contact_name' => 'Office',
                'secondary_contact_phone' => '9' . rand(100000000, 999999999),
                'reference_notes' => 'Generated by seeder',

                'region' => $base['region'],
                'area' => $base['district'],
                'route' => 'Route ' . rand(1, 5),
                'beat' => 'Beat ' . rand(1, 10),
                'territory' => 'Territory ' . rand(1, 3),
                'zone' => 'Zone ' . rand(1, 4),
                'sales_person' => 'Sales Rep ' . rand(1, 5),
                'warehouse' => 'Central Warehouse',

                'aadhaar_last4'   => (string) rand(1000, 9999),
                'kyc_completed'   => (bool) rand(0, 1),
                'kyc_verified_at' => now(),

                'is_active'      => true,
                'is_blacklisted' => false,
                'internal_notes' => 'Seeded customer ' . $i,

                'created_by' => 1,
            ]);

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
