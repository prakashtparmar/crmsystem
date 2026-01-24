<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('address_master', function (Blueprint $table) {
            $table->id();

            $table->string('village_name')->nullable();
            $table->string('pincode', 10)->index();

            // Post Office / Sub Office
            $table->string('post_so_name')->nullable();

            // Taluka / Tehsil
            $table->string('taluka_name')->nullable();

            // District
            $table->string('District_name')->nullable();

            // State
            $table->string('state_name')->nullable();

            $table->timestamps();

            // Helpful indexes for fast lookup
            $table->index(['village_name']);
            $table->index(['taluka_name']);
            $table->index(['District_name']);
            $table->index(['state_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('address_master');
    }
};
