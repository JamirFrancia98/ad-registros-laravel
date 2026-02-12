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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->string('name', 120);                 // Nombre
            $table->string('last_name', 120)->nullable(); // Apellido
            $table->string('nickname', 60)->nullable();   // Chapa
            $table->string('phone', 25)->nullable();      // Teléfono

            $table->string('payment_part', 60)->nullable(); // Ej: "Parte De Pago"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};