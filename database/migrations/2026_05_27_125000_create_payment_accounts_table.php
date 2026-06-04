<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('method'); // Bank Transfer, GCash, PayMaya, etc
            $table->string('account_name'); // e.g., "BCA a.n. Rex Fashion"
            $table->string('account_number'); // Account/card number
            $table->text('instructions')->nullable(); // Additional payment instructions
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_accounts');
    }
};
