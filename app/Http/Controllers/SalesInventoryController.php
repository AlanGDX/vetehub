<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('user_id', Auth::id())
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(10)->withQueryString();

        return view('sales.inventory', compact('products'));
    }
}
