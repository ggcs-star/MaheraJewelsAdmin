<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductVariant;
use App\Models\StockSetting;
use App\Models\PlatformPricing;
use App\Models\OrderItem;
use App\Models\InvoiceItem;
use App\Mail\LowStockAlert;
use Illuminate\Support\Facades\Mail;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check-low';
    protected $description = 'Check low stock from inventory dashboard data';

    public function handle()
    {
        $setting = StockSetting::first();
        if (!$setting) {
            $this->error('Stock alert settings not found.');
            return 1;
        }

        // ✅ Sirf 1 threshold - Sab platforms ke liye
        $threshold = $setting->threshold ?? 10;

        // ✅ Same logic as Inventory Dashboard
        $variants = ProductVariant::with(['product', 'value'])
            ->whereHas('product')
            ->whereHas('platformPricings', function($q) {
                $q->whereHas('platformProduct', function($sub) {
                    $sub->whereIn('platform_id', [3, 4]);
                });
            })
            ->get();

        $lowStockItems = [];

        foreach ($variants as $variant) {
            if ($variant->product == null) continue;

            // ✅ Total Stock = Website Pushed + Offline Pushed (Platform Pricing se)
            $websitePushed = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 3);
                })->sum('quantity');

            $offlinePushed = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) {
                    $q->where('platform_id', 4);
                })->sum('quantity');

            // ✅ Total Sold = Website Orders + Offline Invoices
            $websiteSold = OrderItem::where('variant_id', $variant->id)->sum('quantity');
            $offlineSold = InvoiceItem::where('product_variant_id', $variant->id)->sum('quantity');

            // ✅ Available Stock = Total Pushed - Total Sold
            $totalPushed = $websitePushed + $offlinePushed;
            $totalSold = $websiteSold + $offlineSold;
            $availableStock = max(0, $totalPushed - $totalSold);

            // ✅ Check if low stock (available <= threshold)
            if ($availableStock <= $threshold && $availableStock > 0) {
                $lowStockItems[] = [
                    'product_id' => $variant->product_id,
                    'product_name' => $variant->product->name ?? 'Unknown',
                    'variant_name' => optional($variant->variant)->name . ': ' . optional($variant->value)->value,
                    'color_name' => \App\Helpers\ColorHelper::getColorName($variant->color),
                    'color_hex' => $variant->color ?? '#000000',
                    'available_stock' => $availableStock,
                    'total_pushed' => $totalPushed,
                    'total_sold' => $totalSold,
                    'threshold' => $threshold,
                    'status' => $availableStock <= 0 ? 'Out of Stock' : 'Low Stock',
                ];
            }

            // ✅ Out of Stock check
            if ($availableStock <= 0) {
                $lowStockItems[] = [
                    'product_id' => $variant->product_id,
                    'product_name' => $variant->product->name ?? 'Unknown',
                    'variant_name' => optional($variant->variant)->name . ': ' . optional($variant->value)->value,
                    'color_name' => \App\Helpers\ColorHelper::getColorName($variant->color),
                    'color_hex' => $variant->color ?? '#000000',
                    'available_stock' => 0,
                    'total_pushed' => $totalPushed,
                    'total_sold' => $totalSold,
                    'threshold' => $threshold,
                    'status' => 'Out of Stock',
                ];
            }
        }

        // ✅ Remove duplicates (agar low stock + out of stock dono mein aa gaya)
        $uniqueItems = [];
        foreach ($lowStockItems as $item) {
            $key = $item['product_id'] . '-' . $item['variant_name'] . '-' . $item['color_name'];
            if (!isset($uniqueItems[$key])) {
                $uniqueItems[$key] = $item;
            }
        }
        $lowStockItems = array_values($uniqueItems);

        if (!empty($lowStockItems)) {
            Mail::to($setting->admin_email)->send(new LowStockAlert($lowStockItems, $threshold));
            $this->info('Low stock alert sent. ' . count($lowStockItems) . ' items found.');
        } else {
            $this->info('No low stock items found.');
        }

        return 0;
    }
}