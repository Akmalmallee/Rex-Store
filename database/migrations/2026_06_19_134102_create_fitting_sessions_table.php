<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fitting_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size_recommended', 10)->nullable();
            $table->decimal('fit_score', 5, 2)->nullable();
            $table->json('recommendations')->nullable();
            $table->string('user_feedback')->nullable();
            $table->enum('status', ['pending', 'completed', 'dismissed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fitting_sessions');
    }
};
