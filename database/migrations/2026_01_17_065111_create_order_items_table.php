<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
    $table->engine = 'InnoDB';

    $table->id();

    $table->unsignedBigInteger('order_id');
    $table->unsignedBigInteger('product_id');

    $table->foreign('order_id')
          ->references('id')
          ->on('orders')
          ->cascadeOnDelete();

    $table->foreign('product_id')
          ->references('id')
          ->on('products')
          ->restrictOnDelete();

    $table->string('product_name');
    $table->decimal('price', 12, 2);
    $table->decimal('quantity', 10, 2);
    $table->decimal('tax_rate', 5, 2)->default(0);
    $table->decimal('tax_amount', 12, 2)->default(0);
    $table->decimal('discount_amount', 12, 2)->default(0);
    $table->decimal('total', 12, 2);

    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
