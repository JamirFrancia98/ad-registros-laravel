<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('sales', function (Blueprint $table) {
      if (!Schema::hasColumn('sales', 'customer_id')) {
        $table->foreignId('customer_id')
          ->after('purchase_id')
          ->constrained('customers');
      }

      if (!Schema::hasColumn('sales', 'total_items')) {
        $table->decimal('total_items', 10, 2)->default(0);
      }

      if (!Schema::hasColumn('sales', 'grand_total')) {
        $table->decimal('grand_total', 10, 2)->default(0);
      }
    });
  }

  public function down(): void
  {
    Schema::table('sales', function (Blueprint $table) {
      if (Schema::hasColumn('sales', 'customer_id')) {
        $table->dropConstrainedForeignId('customer_id');
      }
      if (Schema::hasColumn('sales', 'total_items')) {
        $table->dropColumn('total_items');
      }
      if (Schema::hasColumn('sales', 'grand_total')) {
        $table->dropColumn('grand_total');
      }
    });
  }
};