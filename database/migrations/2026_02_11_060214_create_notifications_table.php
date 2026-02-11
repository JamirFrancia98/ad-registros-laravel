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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Mensaje que se verá en el header
            $table->string('title', 120);
            $table->string('message', 255);

            // Datos extra (opcional) para linkear a venta, etc.
            $table->json('data')->nullable();

            // Para “leídas/no leídas” (luego lo usamos)
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
        });
    }

    /**create_customers_table
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
