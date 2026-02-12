<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\IphoneModel;
use App\Models\StorageOption;
use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        // Precio compra entre 4000 y 6000
        $purchasePrice = $this->faker->numberBetween(4000, 6000);

        // Markup fijo en opciones
        $markup = collect([150, 200, 250, 300])->random();

        // Precio venta = compra + markup fijo
        $salePrice = $purchasePrice + $markup;

        // Obtener modelo aleatorio existente
        $iphoneModel = IphoneModel::inRandomOrder()->first()
            ?? IphoneModel::factory()->create();

        // Obtener color que pertenezca a ese modelo
        $color = Color::where('iphone_model_id', $iphoneModel->id)
            ->inRandomOrder()
            ->first();

        // Si no tiene colores, crear uno
        if (!$color) {
            $color = Color::factory()->create([
                'iphone_model_id' => $iphoneModel->id,
            ]);
        }

        return [
            'purchase_date' => $this->faker->dateTimeBetween('-3 months', 'now'),

            'supplier_id' => Supplier::inRandomOrder()->value('id')
                ?? Supplier::factory(),

            'iphone_model_id' => $iphoneModel->id,

            'storage_option_id' => StorageOption::inRandomOrder()->value('id')
                ?? StorageOption::factory(),

            'color_id' => $color->id,

            'imei1' => $this->faker->unique()->numerify('###############'),
            'imei2' => $this->faker->boolean(50)
                ? $this->faker->unique()->numerify('###############')
                : null,

            'serial' => strtoupper($this->faker->bothify('??########')),

            'imei_photo_path' => null,
            'phone_photo_path' => null,

            'purchase_price' => $purchasePrice,
            'sale_price' => $salePrice,
            'markup' => $markup,
        ];
    }
}
