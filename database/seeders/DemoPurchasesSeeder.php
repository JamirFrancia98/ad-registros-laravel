<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoPurchasesSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Crear 5 proveedores (si no existen)
        $supplierNames = [
            'Proveedor 1 - Surquillo',
            'Proveedor 2 - Miraflores',
            'Proveedor 3 - San Isidro',
            'Proveedor 4 - La Victoria',
            'Proveedor 5 - Cercado',
        ];

        foreach ($supplierNames as $name) {
            DB::table('suppliers')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $suppliers = DB::table('suppliers')->orderBy('id')->take(5)->get();
        $models = DB::table('iphone_models')->orderBy('id')->get();
        $storages = DB::table('storage_options')->orderBy('id')->get();

        if ($suppliers->count() < 1 || $models->count() < 1 || $storages->count() < 1) {
            // Si algo no está seeded, salimos para evitar inserts inválidos
            return;
        }

        // 2) Crear 26 compras
        $markups = [150, 200, 250, 300];

        for ($i = 1; $i <= 26; $i++) {
            // Elegir modelo y storage (variando)
            $model = $models[($i - 1) % $models->count()];
            $storage = $storages[($i - 1) % $storages->count()];
            $supplier = $suppliers[($i - 1) % $suppliers->count()];
            $markup = $markups[($i - 1) % count($markups)];

            // Color: tomar uno válido para ese modelo
            $color = DB::table('colors')
                ->where('iphone_model_id', $model->id)
                ->inRandomOrder()
                ->first();

            // Si un modelo no tiene colores cargados, saltamos ese registro
            if (!$color) {
                continue;
            }

            // Fecha de compra (últimos 30 días)
            $purchaseDate = now()->subDays(rand(0, 29))->toDateString();

            // Precios (puedes ajustar rangos)
            $purchasePrice = rand(1400, 5200); // costo
            $salePrice = $purchasePrice + $markup;

            // IMEI / Serie únicos
            // IMEI típico 15 dígitos (aquí generamos 15 num)
            $imei1 = $this->uniqueImei15();
            $imei2 = (rand(0, 1) === 1) ? $this->uniqueImei15() : null;

            $serial = strtoupper(Str::random(12)); // serie tipo aleatoria

            DB::table('purchases')->insert([
                'purchase_date' => $purchaseDate,
                'supplier_id' => $supplier->id,
                'iphone_model_id' => $model->id,
                'storage_option_id' => $storage->id,
                'color_id' => $color->id,

                'imei1' => $imei1,
                'imei2' => $imei2,
                'serial' => $serial,

                // fotos en null (subes manualmente)
                'imei_photo_path' => null,
                'phone_photo_path' => null,

                'purchase_price' => $purchasePrice,
                'sale_price' => $salePrice,
                'markup' => $markup,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function uniqueImei15(): string
    {
        // Genera un IMEI de 15 dígitos y evita duplicados en BD.
        // No es un IMEI "real" validado por Luhn, pero sirve para testing interno.
        do {
            $imei = '';
            for ($i = 0; $i < 15; $i++) {
                $imei .= (string) random_int(0, 9);
            }
            $exists = DB::table('purchases')->where('imei1', $imei)->orWhere('imei2', $imei)->exists();
        } while ($exists);

        return $imei;
    }
}