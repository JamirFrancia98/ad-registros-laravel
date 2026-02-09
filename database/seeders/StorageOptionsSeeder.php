<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorageOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['label' => '64GB',  'gb' => 64],
            ['label' => '128GB', 'gb' => 128],
            ['label' => '256GB', 'gb' => 256],
            ['label' => '512GB', 'gb' => 512],
            ['label' => '1TB',   'gb' => 1024],
        ];

        foreach ($items as $item) {
            DB::table('storage_options')->updateOrInsert(
                ['label' => $item['label']],
                ['gb' => $item['gb'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}