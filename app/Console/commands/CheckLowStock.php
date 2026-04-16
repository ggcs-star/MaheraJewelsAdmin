<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockSetting;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockAlert;
use App\Helpers\ColorHelper;
class CheckLowStock extends Command
{
    protected $signature = 'stock:check-low';
    protected $description = 'Check low stock products and send email alerts';

    public function handle()
    {
        $setting = StockSetting::first();
        
        if(!$setting) {
            $this->error('Stock settings not found. Please configure settings first.');
            return;
        }
        
        $threshold = $setting->threshold;
        $adminEmail = $setting->admin_email;
        
        $products = Product::with(['variants', 'variants.value'])->whereHas('variants')->get();
        
        $lowStockItems = [];
        
        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
                $sold = StockMovement::where('variant_id', $variant->id)
                    ->where('movement', 'OUT')
                    ->sum('quantity');
                
                $remaining = (int) ($variant->quantity ?? 0);
                
                if ($remaining <= $threshold && $remaining > 0) {
                    $colorHex = $variant->color ?? '';
                    $colorName = ColorHelper::getColorName($colorHex);
                    
                    $lowStockItems[] = [
                        'product_name' => $product->name,
                        'variant_name' => $variant->value->value ?? $variant->value->name ?? 'Default',
                        'remaining_qty' => $remaining,
                        'color_name' => $colorName,
                        'color_hex' => $colorHex
                    ];
                }
            }
        }
        
        if (count($lowStockItems) > 0) {
            Mail::to($adminEmail)->send(new LowStockAlert($lowStockItems, $threshold));
            $this->info('Low stock alert sent to ' . $adminEmail);
            $this->info('Total low stock items: ' . count($lowStockItems));
        } else {
            $this->info('No low stock items found. Threshold: ' . $threshold);
        }
    }
    
   
}