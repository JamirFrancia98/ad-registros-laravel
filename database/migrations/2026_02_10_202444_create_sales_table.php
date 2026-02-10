<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_id')
                ->constrained('purchases')
                ->cascadeOnDelete();

            $table->date('sold_at');

            $table->decimal('sold_price', 10, 2);

            $table->string('payment_method', 40)->nullable(); // efectivo, yape, plin, transferencia, tarjeta
            $table->string('channel', 40)->nullable();        // tienda, online, marketplace
            $table->string('customer_name', 120)->nullable();
            $table->string('customer_phone', 30)->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // Evita vender el mismo producto 2 veces
            $table->unique('purchase_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};