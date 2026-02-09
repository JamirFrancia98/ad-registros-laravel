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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->date('purchase_date');

            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->foreignId('iphone_model_id')->constrained('iphone_models')->restrictOnDelete();
            $table->foreignId('storage_option_id')->constrained('storage_options')->restrictOnDelete();
            $table->foreignId('color_id')->constrained('colors')->restrictOnDelete();

            $table->string('imei1', 20)->unique();
            $table->string('imei2', 20)->nullable()->unique();
            $table->string('serial', 50)->nullable()->unique();

            $table->string('imei_photo_path')->nullable();
            $table->string('phone_photo_path')->nullable();

            $table->decimal('purchase_price', 10, 2);
            $table->decimal('sale_price', 10, 2);

            // opcional: guarda el +150/+200/+250/+300 (si quieres auditar la regla)
            $table->unsignedSmallInteger('markup')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
