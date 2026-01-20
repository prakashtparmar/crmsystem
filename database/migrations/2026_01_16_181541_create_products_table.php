<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crop_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('season_id')->nullable()->constrained()->nullOnDelete();

            // Identity
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();   // Enterprise identifier

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Pricing
            $table->decimal('price', 12, 2)->default(0);  // Required for orders
            $table->decimal('cost_price', 12, 2)->nullable(); // Optional (margin / reports)

            // Agri + GST
            $table->string('hsn_code')->nullable();
            $table->decimal('gst_percent', 5, 2)->default(0);
            $table->boolean('is_organic')->default(false);

            // Ordering rules
            $table->decimal('min_order_qty', 10, 2)->default(1);
            $table->decimal('max_order_qty', 10, 2)->nullable();

            // Shelf life (seeds/fertilizers)
            $table->integer('shelf_life_days')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'subcategory_id']);
            $table->index(['brand_id', 'crop_id', 'season_id']);
            $table->index(['is_active']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};
