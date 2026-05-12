<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'seller_id' => 'nullable|exists:users,id',
        ]);

        $query = Sale::with(['items.product', 'seller'])->orderBy('sold_at', 'desc');

        if (!empty($validated['seller_id'])) {
            $query->where('seller_id', $validated['seller_id']);
        }

        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sold_at', [
                Carbon::parse($validated['start_date'])->startOfDay(),
                Carbon::parse($validated['end_date'])->endOfDay(),
            ]);
        } elseif (!empty($validated['start_date'])) {
            $query->where('sold_at', '>=', Carbon::parse($validated['start_date'])->startOfDay());
        } elseif (!empty($validated['end_date'])) {
            $query->where('sold_at', '<=', Carbon::parse($validated['end_date'])->endOfDay());
        }

        return response()->json($query->paginate(20));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'seller']);

        return response()->json($sale);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sold_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
        ]);

        $sellerId = Auth::id();
        $items = $validated['items'];
        $productIds = collect($items)->pluck('product_id')->unique()->values();

        $products = Product::whereIn('id', $productIds)
            ->where('user_id', $sellerId)
            ->get()
            ->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            return response()->json([
                'message' => 'Hay productos que no pertenecen a tu cuenta.',
            ], 422);
        }

        return DB::transaction(function () use ($items, $products, $sellerId, $validated) {
            $itemsCount = 0;
            $total = 0;
            $lineItems = [];

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                $quantity = (int) $item['quantity'];
                $unitPrice = isset($item['unit_price'])
                    ? (float) $item['unit_price']
                    : (float) $product->price;

                if ($product->stock < $quantity) {
                    return response()->json([
                        'message' => 'Stock insuficiente para ' . $product->name . '.',
                    ], 422);
                }

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
                'seller_id' => $sellerId,
                'total' => round($total, 2),
                'items_count' => $itemsCount,
                'sold_at' => $validated['sold_at'] ?? now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($lineItems as $line) {
                $sale->items()->create($line);
                $products->get($line['product_id'])->decrement('stock', $line['quantity']);
            }

            return response()->json([
                'message' => 'Venta registrada correctamente.',
                'sale_id' => $sale->id,
            ], 201);
        });
    }
}
