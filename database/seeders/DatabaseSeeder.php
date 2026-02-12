<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StorageOptionsSeeder::class,
            IphoneModelsSeeder::class,
            ColorsSeeder::class,
            SuppliersSeeder::class,
        ]);
        // Customer::factory(10)->create();
        // Purchase::factory(10)->create();
        User::query()->create([
            'name' => 'Francia Jamir',
            'email' => 'franciajamir@gmail.com',
            'password' => bcrypt('12345'),
        ]);
        User::query()->create([
            'name' => 'Danny Olivera',
            'email' => 'dannyoliveradev@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
