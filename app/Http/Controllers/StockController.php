<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with(['variants', 'variants.value'])
            ->whereHas('variants')
            ->latest()
            ->paginate(10);

        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
                $sold = StockMovement::where('variant_id', $variant->id)
                    ->where('movement', 'OUT')
                    ->sum('quantity');

                $remaining = (int) ($variant->quantity ?? 0);

                $variant->sold_qty = (int) $sold;
                $variant->remaining_qty = $remaining;
                $variant->total_qty = (int) ($variant->sold_qty + $variant->remaining_qty);
            }
        }

        return view('stock.index', compact('products'));
    }
}