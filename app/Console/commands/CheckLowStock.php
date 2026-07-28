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
use App\Models\PlatformProduct;
use App\Models\AmazonOrderItem;
use App\Models\Platform;
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

        $threshold = $setting->threshold ?? 10;
        $amazonPlatformId = Platform::where('display_name', 'Amazon')->value('id');
        if (!$amazonPlatformId) {
            $this->error('Amazon platform not found.');
            return 1;
        }

        $variants = ProductVariant::with(['product', 'value'])
            ->whereHas('product')
            ->where(function ($query) use ($amazonPlatformId) {
                $query->whereHas('platformPricings', function ($q) {
                    $q->whereHas('platformProduct', function ($sub) {
                        $sub->whereIn('platform_id', [3, 4]);
                    });
                });

                $query->orWhereHas('platformProducts', function ($q) use ($amazonPlatformId) {
                    $q->where('platform_id', $amazonPlatformId);
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

            
            $websiteSold = OrderItem::where('variant_id', $variant->id)->sum('quantity');
            $offlineSold = InvoiceItem::where('product_variant_id', $variant->id)->sum('quantity');

            $amazonPushed = PlatformProduct::where('platform_id', $amazonPlatformId)
                ->where('product_variant_id', $variant->id)
                ->value('platform_stock') ?? 0;

            $amazonSold = AmazonOrderItem::where('product_variant_id', $variant->id)
                ->whereHas('order', function ($q) {
                    $q->whereIn('order_status', [
                        'Unshipped',
                        'PartiallyShipped',
                        'Shipped',
                        'InvoiceUnconfirmed',
                    ]);
                })
                ->sum('quantity_ordered');
            
            $totalPushed = $websitePushed + $offlinePushed + $amazonPushed;
            $totalSold = $websiteSold + $offlineSold + $amazonSold;
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