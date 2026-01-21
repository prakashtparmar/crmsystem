<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();

            $table->decimal('quantity', 14, 3)->default(0);
            $table->decimal('reserved_qty', 14, 3)->default(0);

            // Optional: for fast reporting
            $table->index(['product_id']);
            $table->index(['warehouse_id']);

            $table->timestamps();

            $table->unique(['product_id', 'warehouse_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
