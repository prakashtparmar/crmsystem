<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
    $table->id();

    $table->foreignId('order_id')->constrained()->cascadeOnDelete();

    $table->string('carrier')->nullable();
    $table->string('tracking_number')->nullable();
    $table->date('shipped_at')->nullable();
    $table->date('delivered_at')->nullable();

    $table->text('address_snapshot')->nullable();
    $table->enum('status', ['pending','shipped','delivered'])->default('pending');

    $table->timestamps();

    // Existing index
    $table->index(['order_id', 'status']);

    // Improvements (non-breaking)
    $table->index('tracking_number');
    $table->index('shipped_at');
    $table->index('delivered_at');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
