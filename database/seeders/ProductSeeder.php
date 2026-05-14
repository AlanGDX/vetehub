<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $products = [
                ['name' => 'Shampoo Dermatologico', 'sku' => 'SHAM-001', 'price' => 18.50, 'stock' => 50],
                ['name' => 'Antipulgas Canino', 'sku' => 'ANTI-010', 'price' => 24.90, 'stock' => 40],
                ['name' => 'Vitaminas Mascotas', 'sku' => 'VITA-200', 'price' => 12.00, 'stock' => 60],
                ['name' => 'Alimento Premium 2kg', 'sku' => 'ALIM-2K', 'price' => 28.75, 'stock' => 35],
                ['name' => 'Juguete Mordedor', 'sku' => 'JUG-500', 'price' => 9.95, 'stock' => 80],
                ['name' => 'Collar Antipulgas', 'sku' => 'COLL-015', 'price' => 17.25, 'stock' => 45],
                ['name' => 'Arena Sanitaria 5kg', 'sku' => 'ARENA-5K', 'price' => 14.80, 'stock' => 30],
                ['name' => 'Cepillo Dental Mascotas', 'sku' => 'CEP-101', 'price' => 6.90, 'stock' => 90],
                ['name' => 'Snacks Dentales', 'sku' => 'SNACK-220', 'price' => 8.40, 'stock' => 55],
                ['name' => 'Cama Pequena', 'sku' => 'CAMA-S', 'price' => 32.00, 'stock' => 20],
                ['name' => 'Paseador Reflectante', 'sku' => 'PASEO-007', 'price' => 11.50, 'stock' => 70],
                ['name' => 'Comedero Doble', 'sku' => 'COME-2', 'price' => 13.30, 'stock' => 65],
                ['name' => 'Bebedero Antigoteo', 'sku' => 'BEB-030', 'price' => 15.75, 'stock' => 40],
                ['name' => 'Toalla Absorbente', 'sku' => 'TOA-120', 'price' => 10.20, 'stock' => 35],
                ['name' => 'Transportadora Mediana', 'sku' => 'TRANS-M', 'price' => 45.00, 'stock' => 15],
                ['name' => 'Guantes de Aseo', 'sku' => 'GUAN-050', 'price' => 7.60, 'stock' => 60],
                ['name' => 'Corta Unas', 'sku' => 'CORT-015', 'price' => 8.90, 'stock' => 50],
                ['name' => 'Rascador Gato', 'sku' => 'RASC-090', 'price' => 22.40, 'stock' => 25],
            ];

            foreach ($products as $product) {
                Product::create([
                    'user_id' => $user->id,
                    'name' => $product['name'],
                    'sku' => $product['sku'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
