<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Str;

class DemoSalesSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 8 clientes demo
        $customers = [];
        $docs = [
            '70000001',
            '70000002',
            '70000003',
            '70000004',
            '70000005',
            '70000006',
            '70000007',
            '70000008',
        ];

        foreach ($docs as $i => $dni) {
            $customers[] = Customer::firstOrCreate(
                ['dni' => $dni],
                [
                    'first_name' => 'Cliente' . ($i+1),
                    'last_name' => 'Demo',
                    'email' => null,
                    'phone' => '9' . rand(10000000, 99999999),
                    'operator' => ['Claro','Movistar','Entel','Bitel'][rand(0,3)],
                ]
            );
        }

        // Tomar productos NO vendidos
        $products = Purchase::whereDoesntHave('sale')
            ->orderByDesc('purchase_date')
            ->limit(12)
            ->get();

        foreach ($products as $idx => $p) {
            $cust = $customers[$idx % count($customers)];

            // Precio venta demo (puede ser menor o mayor)
            $base = (float)$p->purchase_price;
            $sold = max(0, $base + rand(-200, 450));

            $sale = Sale::create([
                'purchase_id' => $p->id,
                'customer_id' => $cust->id,
                'sold_at' => now()->subDays(rand(0, 30))->toDateString(),
                'sold_price' => $sold,
                'total_items' => 0,
                'grand_total' => 0,
            ]);

            // Items demo opcionales (0 a 3)
            $items = [
                ['Case', rand(50,120)],
                ['Cable', rand(25,60)],
                ['Cubo', rand(35,90)],
                ['Audífonos', rand(60,220)],
            ];

            $totalItems = 0;
            $count = rand(0, 3);

            for ($k=0; $k<$count; $k++) {
                $it = $items[array_rand($items)];
                $qty = rand(1, 2);
                $price = (float)$it[1];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'name' => $it[0],
                    'qty' => $qty,
                    'price' => $price,
                ]);

                $totalItems += ($qty * $price);
            }

            $sale->update([
                'total_items' => $totalItems,
                'grand_total' => (float)$sale->sold_price + $totalItems,
            ]);
        }
    }
}