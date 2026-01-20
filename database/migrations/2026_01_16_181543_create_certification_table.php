<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('type');          // Organic, Govt Approved, ISO, etc.
            $table->string('certificate_no')->nullable();
            $table->string('issued_by')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->string('document_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'type']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('certifications');
    }
};
