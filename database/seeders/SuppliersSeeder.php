<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Proveedor 1', 'payment_part' => null],
            ['name' => 'Proveedor 2', 'payment_part' => null],
        ];

        foreach ($items as $item) {
            DB::table('suppliers')->updateOrInsert(
                ['name' => $item['name']],
                ['payment_part' => $item['payment_part'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}