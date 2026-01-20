<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->enum('method', ['cash','upi','bank_transfer','card','cheque']);
            $table->decimal('amount', 12, 2);
            $table->date('paid_at');

            $table->string('reference')->nullable();
            $table->enum('status', ['pending','completed','failed'])->default('completed');

            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
$table->decimal('balance_after', 12, 2)->nullable();

            $table->timestamps();

            $table->index(['order_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
