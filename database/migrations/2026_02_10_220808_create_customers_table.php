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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('first_name', 80);
            $table->string('last_name', 80);

            $table->string('document_type', 5); // DNI | CE
            $table->string('document_number', 20);

            $table->string('email', 120)->nullable();
            $table->string('phone', 30);
            $table->string('operator', 30)->nullable();

            $table->timestamps();

            $table->unique(['document_type', 'document_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
