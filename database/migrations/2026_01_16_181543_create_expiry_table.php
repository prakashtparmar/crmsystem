<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_lot_id');

            $table->date('expiry_date');
            $table->boolean('is_expired')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('batch_lot_id')
                  ->references('id')
                  ->on('batch_lots')
                  ->cascadeOnDelete();

            $table->index(['batch_lot_id', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expiries');
    }
};
