<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
    $table->engine = 'InnoDB';

    $table->id();

    $table->uuid('uuid')->unique();
    $table->string('order_code')->unique();

    $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

    $table->date('order_date')->default(now());
    $table->date('expected_delivery_at')->nullable();

    $table->decimal('sub_total', 12, 2)->default(0);
    $table->decimal('tax_amount', 12, 2)->default(0);
    $table->decimal('shipping_amount', 12, 2)->default(0);
    $table->decimal('discount_amount', 12, 2)->default(0);
    $table->decimal('grand_total', 12, 2)->default(0);

    $table->enum('status', [
        'draft','confirmed','processing','shipped','delivered','cancelled','refunded'
    ])->default('draft');

    $table->enum('payment_status', [
        'unpaid','partial','paid','failed','refunded'
    ])->default('unpaid');

    $table->string('payment_method')->nullable();
    $table->string('transaction_id')->nullable();

    $table->string('customer_name');
    $table->string('customer_email')->nullable();
    $table->string('customer_phone')->nullable();

    $table->text('billing_address')->nullable();
    $table->text('shipping_address')->nullable();

    $table->timestamp('paid_at')->nullable();
    $table->timestamp('completed_at')->nullable();

    $table->json('meta')->nullable();

    $table->timestamp('confirmed_at')->nullable();
$table->timestamp('cancelled_at')->nullable();

$table->index(['status', 'payment_status']);


    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

    $table->timestamps();
    $table->softDeletes();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
