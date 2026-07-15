<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\PurchaseOrderItem;
use App\Models\PlatformPricing;
use App\Models\InvoiceItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\StockMovement;
class InventoryController extends Controller
{
    public function dashboard(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search', '');
        $brand = $request->get('brand', '');
        $category = $request->get('category', '');
        $supplier = $request->get('supplier', '');
        $stockStatus = $request->get('stock_status', '');
        $perPage = (int) $request->get('per_page', 15);
        
        $variants = ProductVariant::with(['product', 'product.category', 'product.supplier', 'platformPricings.platformProduct'])
            ->whereHas('product')
            ->whereHas('platformPricings', function($q) {
                $q->whereHas('platformProduct', function($sub) {
                    $sub->whereIn('platform_id', [3, 4]);
                });
            })
            ->when($search, function($q) use ($search) {
                $q->whereHas('product', function($sub) use ($search) {
                    $sub->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('sku', 'LIKE', "%{$search}%");
                })->orWhere('sku_suffix', 'LIKE', "%{$search}%");
            })
            ->when($brand, function($q) use ($brand) {
                $q->whereHas('product', function($sub) use ($brand) {
                    $sub->where('brand', $brand);
                });
            })
            ->when($category, function($q) use ($category) {
                $q->whereHas('product', function($sub) use ($category) {
                    $sub->where('category_id', $category);
                });
            })
            ->when($supplier, function($q) use ($supplier) {
                $q->whereHas('product', function($sub) use ($supplier) {
                    $sub->where('supplier_id', $supplier);
                });
            })
            ->get();

        $inventoryData = [];
        $totalStock = 0;
        $totalWebsite = 0;
        $totalOffline = 0;
        $totalWebsiteSold = 0;
        $totalOfflineSold = 0;

        foreach ($variants as $variant) {
            if ($variant->product == null) continue;

            $poQty = PurchaseOrderItem::where('product_variant_id', $variant->id)->sum('quantity');

            $websitePushed = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 3);
                })->sum('quantity');

            $offlineCurrentStock = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 4);
                })->sum('quantity');

            $websiteSold = OrderItem::where('variant_id', $variant->id)->sum('quantity');

            $offlineSold = InvoiceItem::where('product_variant_id', $variant->id)->sum('quantity');

            // ✅ Total Offline Pushed = Sold + Available
            $offlinePushed = $offlineSold + $offlineCurrentStock;

            $websiteAvailable = max(0, $websitePushed - $websiteSold);
            $offlineAvailable = max(0, $offlineCurrentStock);
            $totalSold = $websiteSold + $offlineSold;
            $finalStock = max(0, $poQty - $totalSold);

            $totalStock += $poQty;
            $totalWebsite += $websitePushed;
            $totalOffline += $offlinePushed;
            $totalWebsiteSold += $websiteSold;
            $totalOfflineSold += $offlineSold;

            if ($stockStatus == 'in_stock' && $finalStock <= 0) continue;
            if ($stockStatus == 'low_stock' && ($finalStock > 5 || $finalStock <= 0)) continue;
            if ($stockStatus == 'out_of_stock' && $finalStock > 0) continue;

            if ($filter == 'website') {
                if ($websiteAvailable <= 0 && $websiteSold <= 0) continue;
            } elseif ($filter == 'offline') {
                if ($offlineAvailable <= 0 && $offlineSold <= 0) continue;
            } elseif ($filter == 'low') {
                if ($finalStock > 5 || $finalStock <= 0) continue;
            } elseif ($filter == 'out') {
                if ($finalStock > 0) continue;
            }

            $inventoryData[] = [
                'variant_id' => $variant->id,
                'sku' => $variant->sku_suffix ?? 'N/A',
                'product_name' => $variant->product ? $variant->product->name : 'N/A',
                'brand' => $variant->product ? $variant->product->brand : '',
                'category' => $variant->product && $variant->product->category ? $variant->product->category->name : '',
                'supplier' => $variant->product && $variant->product->supplier ? $variant->product->supplier->name : '',
                'variant_name' => optional($variant->variant)->name . ': ' . optional($variant->value)->value,
                'total_stock' => $poQty,
                'website_pushed' => $websitePushed,
                'website_available' => $websiteAvailable,
                'website_sold' => $websiteSold,
                'offline_pushed' => $offlinePushed,
                'offline_available' => $offlineAvailable,
                'offline_sold' => $offlineSold,
                'total_sold' => $totalSold,
                'final_stock' => $finalStock,
                'status' => $finalStock > 10 ? 'In Stock' : ($finalStock > 0 ? 'Low Stock' : 'Out of Stock'),
                'image_url' => $variant->image_url,
            ];
        }

        $currentPage = (int) $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($inventoryData, $offset, $perPage);
        $totalItems = count($inventoryData);

        $summary = [
            'total_products' => $variants->count(),
            'total_stock' => $totalStock,
            'total_website' => $totalWebsite,
            'total_offline' => $totalOffline,
            'total_website_sold' => $totalWebsiteSold,
            'total_offline_sold' => $totalOfflineSold,
            'total_sold' => $totalWebsiteSold + $totalOfflineSold,
            'total_available' => max(0, $totalStock - $totalWebsiteSold - $totalOfflineSold),
        ];

        $brands = \App\Models\Product::whereNotNull('brand')->distinct()->pluck('brand');
        $categories = \App\Models\Category::select('id', 'name')->get();
        $suppliers = \App\Models\Supplier::select('id', 'name')->where('status', 'active')->get();

        return view('inventory.dashboard', compact('paginatedData', 'inventoryData', 'summary', 'totalItems', 'perPage', 'currentPage', 'filter', 'search', 'brand', 'category', 'supplier', 'stockStatus', 'brands', 'categories', 'suppliers'));
    }

//     public function details($variantId)
// {
//     $variant = ProductVariant::with(['product', 'platformPricings.platformProduct'])->findOrFail($variantId);

//     $poQty = PurchaseOrderItem::where('product_variant_id', $variantId)->sum('quantity');
//     $websitePushed = PlatformPricing::where('product_variant_id', $variantId)
//         ->whereHas('platformProduct', function($q) {
//             $q->where('platform_id', 3);
//         })->sum('quantity');
//     $offlineCurrentStock = PlatformPricing::where('product_variant_id', $variantId)
//         ->whereHas('platformProduct', function($q) {
//             $q->where('platform_id', 4);
//         })->sum('quantity');
//     $offlineSold = InvoiceItem::where('product_variant_id', $variantId)->sum('quantity');
//     $offlinePushed = $offlineSold + $offlineCurrentStock;

//     $stockMovements = StockMovement::where('variant_id', $variantId)
//         ->with(['platform'])
//         ->orderBy('created_at', 'desc')
//         ->limit(50)
//         ->get();

//     return view('inventory.details', compact('variant', 'poQty', 'websitePushed', 'offlinePushed', 'stockMovements'));
// }
}