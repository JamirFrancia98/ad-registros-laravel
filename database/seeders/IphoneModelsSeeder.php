<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IphoneModelsSeeder extends Seeder
{
    public function run(): void
    {
        $models = [
            'iPhone 12',
            'iPhone 12 Pro',
            'iPhone 12 Pro Max',
            'iPhone 13',
            'iPhone 13 Pro',
            'iPhone 13 Pro Max',
            'iPhone 14',
            'iPhone 14 Pro',
            'iPhone 14 Pro Max',
            'iPhone 15',
            'iPhone 15 Pro',
            'iPhone 15 Pro Max',
            'iPhone 16',
            'iPhone 16 Pro',
            'iPhone 16 Pro Max',
            'iPhone 17 Pro Max',
        ];

        foreach ($models as $name) {
            DB::table('iphone_models')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}