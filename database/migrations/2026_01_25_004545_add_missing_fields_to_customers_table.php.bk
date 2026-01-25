<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('customers', function (Blueprint $table) {

        $table->string('middle_name')->nullable()->after('first_name');
        $table->string('phone_number_2')->nullable()->after('mobile');
        $table->string('relative_phone')->nullable()->after('phone_number_2');

        $table->string('business_type')->nullable();
        $table->string('registration_number')->nullable();
        $table->string('website')->nullable();
        $table->year('established_year')->nullable();
        $table->decimal('annual_turnover', 14, 2)->nullable();

        $table->string('post_office')->nullable();

        $table->string('farming_method')->nullable();
        $table->string('soil_type')->nullable();

        $table->integer('payment_terms')->nullable();
        $table->string('preferred_payment_mode')->nullable();
        $table->string('bank_name')->nullable();
        $table->string('account_number')->nullable();
        $table->string('ifsc_code')->nullable();
        $table->string('billing_cycle')->nullable();

        $table->string('referred_by')->nullable();
        $table->string('reference_type')->nullable();
        $table->string('reference_name')->nullable();
        $table->string('reference_phone')->nullable();
        $table->string('secondary_contact_name')->nullable();
        $table->string('secondary_contact_phone')->nullable();
        $table->text('reference_notes')->nullable();

        $table->string('region')->nullable();
        $table->string('area')->nullable();
        $table->string('route')->nullable();
        $table->string('beat')->nullable();
        $table->string('territory')->nullable();
        $table->string('zone')->nullable();
        $table->string('sales_person')->nullable();
        $table->string('warehouse')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
