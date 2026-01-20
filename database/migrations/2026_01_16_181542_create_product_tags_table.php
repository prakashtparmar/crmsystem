<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('tag'); // e.g., Best Seller, New Arrival, Govt Approved

            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'tag']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_tags');
    }
};
