<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $products = Product::where('user_id', $user->id)->get();

            if ($products->isEmpty()) {
                continue;
            }

            for ($i = 0; $i < 8; $i++) {
                $itemsCount = 0;
                $total = 0;
                $lineItems = [];
                $selected = $products->random(min(3, $products->count()));

                foreach ($selected as $product) {
                    $quantity = rand(1, 3);
                    $unitPrice = (float) $product->price;
                    $lineTotal = round($quantity * $unitPrice, 2);

                    $itemsCount += $quantity;
                    $total += $lineTotal;

                    $lineItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total' => $lineTotal,
                    ];
                }

                $sale = Sale::create([
                    'seller_id' => $user->id,
                    'total' => round($total, 2),
                    'items_count' => $itemsCount,
                    'sold_at' => now()->subDays(rand(0, 14))->addMinutes(rand(0, 900)),
                    'notes' => rand(0, 1) ? 'Venta generada para pruebas.' : null,
                ]);

                foreach ($lineItems as $line) {
                    $sale->items()->create($line);
                    $product = $products->firstWhere('id', $line['product_id']);

                    if ($product && $product->stock >= $line['quantity']) {
                        $product->decrement('stock', $line['quantity']);
                    }
                }
            }
        }
    }
}
