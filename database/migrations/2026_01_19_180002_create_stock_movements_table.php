<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();

            $table->enum('type', [
                'in',           // purchase, return
                'out',          // sale, issue
                'adjust',       // damage, correction
                'transfer_in',
                'transfer_out',
                'reserve',      // order placed (stock blocked)
                'release',      // order cancelled / returned
            ]);

            $table->decimal('quantity', 14, 3);
            $table->decimal('balance_after', 14, 3);

            $table->string('reference_type')->nullable(); // order, purchase, etc.
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'warehouse_id']);
            $table->index(['reference_type', 'reference_id']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
