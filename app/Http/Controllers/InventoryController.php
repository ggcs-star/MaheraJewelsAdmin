<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\PurchaseOrderItem;
use App\Models\PlatformPricing;
use App\Models\InvoiceItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function dashboard(Request $request)
    {
        $variants = ProductVariant::with(['product', 'platformPricings.platformProduct'])->get();

        $inventoryData = [];
        $totalStock = 0;
        $totalWebsite = 0;
        $totalOffline = 0;
        $totalWebsiteSold = 0;
        $totalOfflineSold = 0;

        foreach ($variants as $variant) {
            $poQty = PurchaseOrderItem::where('product_variant_id', $variant->id)->sum('quantity');

            $websitePushed = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 3);
                })->sum('quantity');

            $offlinePushed = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 4);
                })->sum('quantity');

            // ✅ Website Sold - OrderItem se fetch karo
            $websiteSold = OrderItem::where('variant_id', $variant->id)->sum('quantity');

            // ✅ Offline Sold - InvoiceItem se fetch karo
            $offlineSold = InvoiceItem::where('product_variant_id', $variant->id)->sum('quantity');

            $websiteAvailable = $websitePushed - $websiteSold;
            $offlineAvailable = $offlinePushed - $offlineSold;
            $finalStock = $poQty - $websitePushed - $offlinePushed;

            $totalStock += $poQty;
            $totalWebsite += $websitePushed;
            $totalOffline += $offlinePushed;
            $totalWebsiteSold += $websiteSold;
            $totalOfflineSold += $offlineSold;

            $inventoryData[] = [
                'variant_id' => $variant->id,
                'sku' => $variant->sku_suffix,
                'product_name' => $variant->product->name ?? 'N/A',
                'variant_name' => optional($variant->variant)->name . ': ' . optional($variant->value)->value,
                'total_stock' => $poQty,
                'website_pushed' => $websitePushed,
                'website_available' => $websiteAvailable,
                'website_sold' => $websiteSold,
                'offline_pushed' => $offlinePushed,
                'offline_available' => $offlineAvailable,
                'offline_sold' => $offlineSold,
                'total_sold' => $websiteSold + $offlineSold,
                'final_stock' => $finalStock,
                'status' => $finalStock > 10 ? 'In Stock' : ($finalStock > 0 ? 'Low Stock' : 'Out of Stock'),
                'image_url' => $variant->image_url,
            ];
        }

        $summary = [
            'total_products' => $variants->count(),
            'total_stock' => $totalStock,
            'total_website' => $totalWebsite,
            'total_offline' => $totalOffline,
            'total_website_sold' => $totalWebsiteSold,
            'total_offline_sold' => $totalOfflineSold,
            'total_sold' => $totalWebsiteSold + $totalOfflineSold,
            'total_available' => $totalStock - $totalWebsite - $totalOffline,
        ];

        return view('inventory.dashboard', compact('inventoryData', 'summary'));
    }

    public function details($variantId)
    {
        $variant = ProductVariant::with(['product', 'platformPricings.platformProduct'])->findOrFail($variantId);

        $poQty = PurchaseOrderItem::where('product_variant_id', $variantId)->sum('quantity');

        $websitePushed = PlatformPricing::where('product_variant_id', $variantId)
            ->whereHas('platformProduct', function($q) {
                $q->where('platform_id', 3);
            })->sum('quantity');

        $offlinePushed = PlatformPricing::where('product_variant_id', $variantId)
            ->whereHas('platformProduct', function($q) {
                $q->where('platform_id', 4);
            })->sum('quantity');

        return view('inventory.details', compact('variant', 'poQty', 'websitePushed', 'offlinePushed'));
    }
}