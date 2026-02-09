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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iphone_model_id')->constrained('iphone_models')->cascadeOnDelete();
            $table->string('name', 40); // ej: Midnight, Blue, etc.
            $table->timestamps();

            $table->unique(['iphone_model_id', 'name']); // evita duplicados por modelo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
