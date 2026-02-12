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
            $table->foreignId('customer_id')
                ->constrained('customers');
            $table->date('sold_at');
            $table->decimal('sold_price', 10, 2);
            $table->string('payment_method', 40)->nullable(); // efectivo, yape, plin, transferencia, tarjeta
            $table->string('channel', 40)->nullable();        // tienda, online, marketplace
            $table->text('notes')->nullable();
            $table->decimal('total_items', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->timestamps();
            $table->unique('purchase_id'); // Evita vender el mismo producto 2 veces
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
