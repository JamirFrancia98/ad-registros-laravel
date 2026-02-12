<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentType = $this->faker->randomElement(['DNI', 'C.E', 'Otro']);

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone' => '9' . $this->faker->numerify('########'), // Celular Perú
            'email' => $this->faker->unique()->safeEmail(),
            'document_type' => $documentType,
            'document_number' => $documentType === 'DNI'
                ? $this->faker->numerify('########') // 8 dígitos
                : $this->faker->bothify('??######'),
            'operator' => $this->faker->randomElement([
                'Claro',
                'Movistar',
                'Entel',
                'Bitel',
                'Otro'
            ]),
        ];
    }
}
