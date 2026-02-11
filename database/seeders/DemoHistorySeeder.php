<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\SaleItem;

class DemoHistorySeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Ajustes
        $daysBack = 150;              // ~5 meses
        $minPurchasesPerDay = 0;
        $maxPurchasesPerDay = 3;      // 0..3 compras por día
        $sellRate = 0.80;             // 80% de compras terminan vendidas
        $minDaysToSell = 0;           // mismo día
        $maxDaysToSell = 25;          // hasta 25 días después

        // Requiere que ya existan estas tablas llenas (tú ya las seedearon):
        $supplierIds = DB::table('suppliers')->pluck('id')->all();
        $modelIds    = DB::table('iphone_models')->pluck('id')->all();
        $storageIds  = DB::table('storage_options')->pluck('id')->all();
        $colorByModel = DB::table('colors')
            ->select('id','iphone_model_id')
            ->get()
            ->groupBy('iphone_model_id')
            ->map(fn($rows) => $rows->pluck('id')->all())
            ->toArray();

        if (empty($supplierIds) || empty($modelIds) || empty($storageIds) || empty($colorByModel)) {
            $this->command?->error("Faltan seeds base: suppliers / iphone_models / storage_options / colors.");
            return;
        }

        // ⚠️ Opcional: limpiar data previa (descomenta si quieres data “desde cero”)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('sale_items')->truncate();
        // DB::table('sales')->truncate();
        // DB::table('customers')->truncate();
        // DB::table('purchases')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = fake();

        // Algunos accesorios comunes
        $accessories = [
            'Cargador', 'Cable', 'Case', 'Audífonos', 'Protector de pantalla', 'Adaptador', 'Powerbank'
        ];

        $createdPurchases = 0;
        $createdSales = 0;

        for ($d = $daysBack; $d >= 0; $d--) {
            $purchaseDate = now()->subDays($d)->toDateString();
            $n = random_int($minPurchasesPerDay, $maxPurchasesPerDay);

            for ($i = 0; $i < $n; $i++) {
                $supplierId = $supplierIds[array_rand($supplierIds)];
                $modelId    = $modelIds[array_rand($modelIds)];
                $storageId  = $storageIds[array_rand($storageIds)];
                $colorIdsForModel = $colorByModel[$modelId] ?? null;

                // Si ese modelo no tiene colores sembrados, saltamos
                if (!$colorIdsForModel) continue;

                $colorId = $colorIdsForModel[array_rand($colorIdsForModel)];

                // Precios: costo base + margen
                $purchasePrice = random_int(1600, 6200);
                $markup = [150,200,250,300][array_rand([150,200,250,300])]; // solo para guardar el valor, no para calcular
                $salePriceExpected = $purchasePrice + random_int(150, 450);

                // IMEI únicos
                $imei1 = $this->uniqueImei();
                $imei2 = (random_int(0, 100) < 40) ? $this->uniqueImei() : null; // 40% dual sim
                $serial = (random_int(0, 100) < 70) ? Str::upper(Str::random(12)) : null;

                // Crear compra
                /** @var Purchase $purchase */
                $purchase = Purchase::create([
                    'purchase_date' => $purchaseDate,
                    'supplier_id' => $supplierId,
                    'iphone_model_id' => $modelId,
                    'storage_option_id' => $storageId,
                    'color_id' => $colorId,
                    'imei1' => $imei1,
                    'imei2' => $imei2,
                    'serial' => $serial,
                    'purchase_price' => $purchasePrice,
                    'sale_price' => $salePriceExpected,
                    'markup' => $markup,
                    'imei_photo_path' => null,
                    'phone_photo_path' => null,
                ]);

                $createdPurchases++;

                // ¿Se vende?
                if (mt_rand() / mt_getrandmax() <= $sellRate) {

                    // Cliente (DNI único)
                    $dni = $this->uniqueDni();
                    $customer = Customer::firstOrCreate(
                        ['dni' => $dni],
                        [
                            'first_name' => $faker->firstName(),
                            'last_name'  => $faker->lastName(),
                            'email'      => (random_int(0,100) < 55) ? $faker->safeEmail() : null,
                            'phone'      => '9' . random_int(10000000, 99999999),
                            'operator'   => $faker->randomElement(['Movistar','Claro','Entel','Bitel', null]),
                        ]
                    );

                    $soldAt = now()
                        ->parse($purchaseDate)
                        ->addDays(random_int($minDaysToSell, $maxDaysToSell))
                        ->toDateString();

                    // Precio real: puede ser menor o mayor que "esperado"
                    $soldPrice = max(0, $purchasePrice + random_int(-150, 650));

                    /** @var Sale $sale */
                    $sale = Sale::create([
                        'purchase_id' => $purchase->id,
                        'customer_id' => $customer->id,
                        'sold_at' => $soldAt,
                        'sold_price' => $soldPrice,
                        'total_items' => 0,
                        'grand_total' => 0,
                    ]);

                    // Accesorios (0 a 3 items en ~55% de ventas)
                    $itemsTotal = 0;
                    if (random_int(0,100) < 55) {
                        $itemsCount = random_int(0, 3);
                        for ($k = 0; $k < $itemsCount; $k++) {
                            $name = $accessories[array_rand($accessories)];
                            $qty  = random_int(1, 2);
                            $price = random_int(20, 220);

                            SaleItem::create([
                                'sale_id' => $sale->id,
                                'name' => $name,
                                'qty' => $qty,
                                'price' => $price,
                            ]);

                            $itemsTotal += ($qty * $price);
                        }
                    }

                    $sale->update([
                        'total_items' => $itemsTotal,
                        'grand_total' => ((float)$soldPrice) + $itemsTotal,
                    ]);

                    $createdSales++;
                }
            }
        }

        $this->command?->info("✅ DemoHistorySeeder listo: $createdPurchases compras, $createdSales ventas.");
    }

    private function uniqueImei(): string
    {
        // 15 dígitos (simple). Suficiente para demo.
        return (string) random_int(100000000000000, 999999999999999);
    }

    private function uniqueDni(): string
    {
        // 8 dígitos
        return (string) random_int(70000000, 79999999);
    }
}