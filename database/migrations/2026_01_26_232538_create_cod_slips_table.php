<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database/migrations/xxxx_xx_xx_create_cod_slips_table.php

Schema::create('cod_slips', function (Blueprint $table) {
    $table->id();

    $table->foreignId('order_id')->constrained()->cascadeOnDelete();

    $table->string('customer_name');
    $table->string('mobile');
    $table->string('alt_mobile')->nullable();
    $table->text('address');

    $table->decimal('cod_amount', 10, 2);

    $table->string('scheme')->default('BNPL SCHEME');
    $table->string('post_type')->default('BUSINESS PARCEL OR SPEED POST PARCEL');
    $table->string('customer_id')->default('1211658094');

    $table->timestamps();

    // only one slip per order
    $table->unique('order_id');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cod_slips');
    }
};
