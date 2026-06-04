<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->enum('status', ['pending', 'paid', 'process', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('coupon_code')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('phone');
            $table->text('notes')->nullable();
            $table->string('shipping_courier')->nullable();
            $table->string('payment_method');
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
