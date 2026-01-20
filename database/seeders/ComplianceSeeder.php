<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{ProductVariant, BatchLot, Expiry, Certification};

class ComplianceSeeder extends Seeder
{
    public function run(): void
    {
        $variant = ProductVariant::first();

        $batch = BatchLot::create([
            'product_variant_id' => $variant->id,
            'batch_number' => 'BATCH-001',
            'manufactured_at' => now()->subMonths(2),
            'quantity' => 500,
        ]);

        Expiry::create([
            'batch_lot_id' => $batch->id,
            'expiry_date' => now()->addMonths(10),
            'is_expired' => false,
        ]);

        Certification::create([
            'product_id' => $variant->product_id,
            'type' => 'Govt Approved',
            'certificate_no' => 'GOV-2025-001',
            'issued_by' => 'Agriculture Dept.',
            'valid_from' => now()->subYear(),
            'valid_to' => now()->addYear(),
            'document_path' => 'certificates/govt.pdf',
        ]);
    }
}
