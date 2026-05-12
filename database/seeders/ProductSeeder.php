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
