<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\OrderItem;
use App\Helpers\ColorHelper;

class StockController extends Controller
{
    public function index()
    {
        // ✅ Sirf wahi products jo purchase orders mein hain
        $products = Product::whereHas('variants.purchaseOrderItems')
            ->with(['variants', 'variants.value', 'variants.purchaseOrderItems'])
            ->latest()
            ->paginate(10);

        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
                
                // ✅ Website Stock = Purchase Orders - Orders (Website)
                $websiteStock = PurchaseOrderItem::where('product_variant_id', $variant->id)
                    ->whereHas('purchaseOrder', function($q) {
                        $q->where('platform', 'website');
                    })
                    ->sum('quantity') 
                    - OrderItem::where('product_variant_id', $variant->id)
                        ->whereHas('order', function($q) {
                            $q->where('platform', 'website');
                        })
                        ->sum('quantity');

                // ✅ Offline Stock = Purchase Orders - Orders (Offline)
                $offlineStock = PurchaseOrderItem::where('product_variant_id', $variant->id)
                    ->whereHas('purchaseOrder', function($q) {
                        $q->where('platform', 'offline');
                    })
                    ->sum('quantity') 
                    - OrderItem::where('product_variant_id', $variant->id)
                        ->whereHas('order', function($q) {
                            $q->where('platform', 'offline');
                        })
                        ->sum('quantity');

                $totalStock = $websiteStock + $offlineStock;
                $sold = OrderItem::where('product_variant_id', $variant->id)->sum('quantity');

                $variant->sold_qty = (int) $sold;
                $variant->remaining_qty = max(0, $totalStock);
                $variant->total_qty = (int) ($variant->sold_qty + $variant->remaining_qty);
                $variant->color_name = ColorHelper::getColorName($variant->color);
            }
        }

        return view('stock.index', compact('products'));
    }
}