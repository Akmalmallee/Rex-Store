<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('model_file');
            $table->string('thumbnail')->nullable();
            $table->decimal('scale_x', 8, 4)->default(1.0000);
            $table->decimal('scale_y', 8, 4)->default(1.0000);
            $table->decimal('scale_z', 8, 4)->default(1.0000);
            $table->decimal('position_x', 8, 4)->default(0.0000);
            $table->decimal('position_y', 8, 4)->default(0.0000);
            $table->decimal('position_z', 8, 4)->default(0.0000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_models');
    }
};
