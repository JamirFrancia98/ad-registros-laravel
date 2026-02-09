<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorsSeeder extends Seeder
{
    public function run(): void
    {
        $modelMap = DB::table('iphone_models')->pluck('id', 'name'); // name => id

        // Colores ejemplo por familia (puedes ajustar luego)
        $colorsByModel = [
            'iPhone 12' => ['Black', 'White', 'Red', 'Green', 'Blue', 'Purple'],
            'iPhone 12 Pro' => ['Graphite', 'Silver', 'Gold', 'Pacific Blue'],
            'iPhone 12 Pro Max' => ['Graphite', 'Silver', 'Gold', 'Pacific Blue'],

            'iPhone 13' => ['Midnight', 'Starlight', 'Blue', 'Pink', 'Red', 'Green'],
            'iPhone 13 Pro' => ['Graphite', 'Silver', 'Gold', 'Sierra Blue', 'Alpine Green'],
            'iPhone 13 Pro Max' => ['Graphite', 'Silver', 'Gold', 'Sierra Blue', 'Alpine Green'],

            'iPhone 14' => ['Midnight', 'Starlight', 'Blue', 'Purple', 'Red', 'Yellow'],
            'iPhone 14 Pro' => ['Space Black', 'Silver', 'Gold', 'Deep Purple'],
            'iPhone 14 Pro Max' => ['Space Black', 'Silver', 'Gold', 'Deep Purple'],

            'iPhone 15' => ['Black', 'Blue', 'Green', 'Yellow', 'Pink'],
            'iPhone 15 Pro' => ['Black Titanium', 'White Titanium', 'Blue Titanium', 'Natural Titanium'],
            'iPhone 15 Pro Max' => ['Black Titanium', 'White Titanium', 'Blue Titanium', 'Natural Titanium'],

            // Puedes ajustar estos cuando quieras (por ahora quedan como base)
            'iPhone 16' => ['Black', 'White', 'Blue', 'Green', 'Pink'],
            'iPhone 16 Pro' => ['Black Titanium', 'White Titanium', 'Natural Titanium', 'Blue Titanium'],
            'iPhone 16 Pro Max' => ['Black Titanium', 'White Titanium', 'Natural Titanium', 'Blue Titanium'],

            'iPhone 17 Pro Max' => ['Black', 'Silver', 'Titanium'],
        ];

        foreach ($colorsByModel as $modelName => $colors) {
            $modelId = $modelMap[$modelName] ?? null;
            if (!$modelId) continue;

            foreach ($colors as $colorName) {
                DB::table('colors')->updateOrInsert(
                    ['iphone_model_id' => $modelId, 'name' => $colorName],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}