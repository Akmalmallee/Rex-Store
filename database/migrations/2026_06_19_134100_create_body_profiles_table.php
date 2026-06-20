<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('body_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('height', 5, 1)->nullable()->comment('cm');
            $table->decimal('weight', 5, 1)->nullable()->comment('kg');
            $table->enum('body_type', ['slim', 'average', 'athletic', 'plus', 'hourglass', 'pear', 'apple', 'rectangle'])->nullable();
            $table->string('preferred_size', 10)->nullable();
            $table->decimal('shoulder_width', 5, 1)->nullable()->comment('cm');
            $table->decimal('chest_circumference', 5, 1)->nullable()->comment('cm');
            $table->decimal('waist_circumference', 5, 1)->nullable()->comment('cm');
            $table->decimal('hip_circumference', 5, 1)->nullable()->comment('cm');
            $table->decimal('inseam', 5, 1)->nullable()->comment('cm');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('body_profiles');
    }
};
