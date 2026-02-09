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
        Schema::create('storage_options', function (Blueprint $table) {
            $table->id();
            $table->string('label', 20)->unique(); // 64GB, 128GB...
            $table->unsignedSmallInteger('gb');    // 64,128,256,512,1024
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_options');
    }
};
