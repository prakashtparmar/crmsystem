<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('to_warehouse_id')->constrained('warehouses')->cascadeOnDelete();

            $table->decimal('quantity', 14, 3);
            $table->enum('status', ['pending', 'in_transit', 'completed', 'cancelled'])->default('pending');

            $table->string('transfer_code')->unique();
            $table->text('remarks')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->index(['product_id']);
            $table->index(['from_warehouse_id']);
            $table->index(['to_warehouse_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
