<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('batch_lots', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedBigInteger('id', true); // explicit UNSIGNED BIGINT PK
            $table->unsignedBigInteger('product_variant_id');

            $table->string('batch_number')->index();
            $table->date('manufactured_at')->nullable();
            $table->integer('quantity');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_variant_id')
                  ->references('id')
                  ->on('product_variants')
                  ->cascadeOnDelete();

            $table->unique(['product_variant_id', 'batch_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_lots');
    }
};
