<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // System Identifiers
            $table->uuid('uuid')->unique();
            $table->string('customer_code')->unique(); // CUST-000001

            // Identity
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();

            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('mobile', 20)->unique();
            $table->string('email')->nullable()->index();

            // Extra Contacts (UI)
            $table->string('phone_number_2')->nullable();
            $table->string('relative_phone')->nullable();

            // Classification
            $table->enum('type', ['farmer', 'buyer', 'vendor', 'dealer'])->default('farmer');
            $table->enum('category', ['individual', 'business'])->default('individual');

            // Business Details
            $table->string('company_name')->nullable();
            $table->string('gst_number')->nullable()->index();
            $table->string('pan_number')->nullable();

            // Extra Business (UI)
            $table->string('business_type')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('website')->nullable();
            $table->year('established_year')->nullable();
            $table->decimal('annual_turnover', 14, 2)->nullable();

            // Address (Primary)
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('village')->nullable();
            $table->string('taluka')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable()->index();
            $table->string('country')->default('India');
            $table->string('pincode', 10)->nullable()->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Address Extra (UI)
            $table->string('post_office')->nullable();

            // Agriculture Profile
            $table->decimal('land_area', 10, 2)->nullable();
            $table->string('land_unit')->default('acre');
            $table->json('primary_crops')->nullable();
            $table->json('secondary_crops')->nullable();
            $table->string('irrigation_type')->nullable(); // borewell, canal, rainfed

            // Agriculture Extra (UI)
            $table->string('farming_method')->nullable();
            $table->string('soil_type')->nullable();

            // Financial / Credit
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('outstanding_balance', 12, 2)->default(0);
            $table->date('credit_valid_till')->nullable();

            // Financial Extra (UI)
            $table->integer('payment_terms')->nullable();
            $table->string('preferred_payment_mode')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('billing_cycle')->nullable();

            // Reference / Contacts (UI)
            $table->string('referred_by')->nullable();
            $table->string('reference_type')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('reference_phone')->nullable();
            $table->string('secondary_contact_name')->nullable();
            $table->string('secondary_contact_phone')->nullable();
            $table->text('reference_notes')->nullable();

            // Location / Routing (UI)
            $table->string('region')->nullable();
            $table->string('area')->nullable();
            $table->string('route')->nullable();
            $table->string('beat')->nullable();
            $table->string('territory')->nullable();
            $table->string('zone')->nullable();
            $table->string('sales_person')->nullable();
            $table->string('warehouse')->nullable();

            // KYC & Compliance
            $table->string('aadhaar_last4')->nullable();
            $table->boolean('kyc_completed')->default(false);
            $table->timestamp('kyc_verified_at')->nullable();

            // Engagement
            $table->date('first_purchase_at')->nullable();
            $table->date('last_purchase_at')->nullable();
            $table->unsignedInteger('orders_count')->default(0);

            // Status & Control
            $table->boolean('is_active')->default(true);
            $table->boolean('is_blacklisted')->default(false);
            $table->text('internal_notes')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['type', 'is_active']);
            $table->index(['state', 'district']);
            $table->index(['customer_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
