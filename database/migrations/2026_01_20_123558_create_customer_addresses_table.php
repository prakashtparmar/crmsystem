<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            // Type & control
            $table->enum('type', ['billing', 'shipping', 'both'])->default('shipping');
            $table->boolean('is_default')->default(false);
            $table->string('label')->nullable(); // Home, Office, Farm, Warehouse

            // Contact
            $table->string('contact_name')->nullable();
            $table->string('contact_phone', 20)->nullable();

            // Address
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('village')->nullable();
            $table->string('taluka')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable()->index();
            $table->string('country')->default('India');
            $table->string('pincode', 10)->nullable()->index();

            // Extra Address (UI)
            $table->string('post_office')->nullable();

            // Location / Routing (UI)
            $table->string('region')->nullable();
            $table->string('area')->nullable();
            $table->string('route')->nullable();
            $table->string('beat')->nullable();
            $table->string('territory')->nullable();
            $table->string('zone')->nullable();
            $table->string('warehouse')->nullable();

            // Geo
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
