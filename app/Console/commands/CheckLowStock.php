<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockSetting;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockAlert;

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
                    $colorName = $this->getColorName($colorHex);
                    
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
    
    private function getColorName($hex)
    {
        if (empty($hex)) {
            return 'N/A';
        }
        
        $hex = strtoupper($hex);
        
        $colorMap = [
            '#000000' => 'Black',
            '#FFFFFF' => 'White',
            '#FF0000' => 'Red',
            '#00FF00' => 'Green',
            '#0000FF' => 'Blue',
            '#FFFF00' => 'Yellow',
            '#FF00FF' => 'Magenta',
            '#00FFFF' => 'Cyan',
            '#808080' => 'Gray',
            '#800000' => 'Maroon',
            '#808000' => 'Olive',
            '#008000' => 'Green',
            '#800080' => 'Purple',
            '#000080' => 'Navy',
            '#008080' => 'Teal',
            '#FFA500' => 'Orange',
            '#FFC0CB' => 'Pink',
            '#FFD700' => 'Gold',
            '#A52A2A' => 'Brown',
            '#2069DF' => 'Blue',
            '#DF2A2A' => 'Red',
            '#B75C5C' => 'Rose',
            '#2FBC59' => 'Green',
            '#8A8728' => 'Olive',
            '#A73939' => 'Maroon',
            '#1F48A8' => 'Navy Blue',
            '#313F34' => 'Dark Green',
            '#BA2C2C' => 'Crimson',
            '#993D3D' => 'Brown Red',
            '#E74C3C' => 'Red',
            '#3498DB' => 'Blue',
            '#2ECC71' => 'Green',
            '#F39C12' => 'Orange',
            '#9B59B6' => 'Purple',
            '#1ABC9C' => 'Teal',
            '#E67E22' => 'Orange',
            '#95A5A6' => 'Gray',
            '#D35400' => 'Pumpkin',
            '#C0392B' => 'Red',
            '#16A085' => 'Green',
            '#27AE60' => 'Green',
            '#2980B9' => 'Blue',
            '#8E44AD' => 'Purple',
            '#2C3E50' => 'Navy',
            '#F1C40F' => 'Yellow',
            '#E91E63' => 'Pink',
            '#9C27B0' => 'Purple',
            '#673AB7' => 'Indigo',
            '#3F51B5' => 'Blue',
            '#2196F3' => 'Blue',
            '#03A9F4' => 'Light Blue',
            '#00BCD4' => 'Cyan',
            '#009688' => 'Teal',
            '#4CAF50' => 'Green',
            '#8BC34A' => 'Light Green',
            '#CDDC39' => 'Lime',
            '#FFEB3B' => 'Yellow',
            '#FFC107' => 'Amber',
            '#FF9800' => 'Orange',
            '#FF5722' => 'Deep Orange',
            '#795548' => 'Brown',
            '#607D8B' => 'Blue Gray'
        ];
        
        if (isset($colorMap[$hex])) {
            return $colorMap[$hex];
        }
        
        if (strlen($hex) == 7 && $hex[0] == '#') {
            $r = hexdec(substr($hex, 1, 2));
            $g = hexdec(substr($hex, 3, 2));
            $b = hexdec(substr($hex, 5, 2));
            
            if ($r > 200 && $g < 100 && $b < 100) return 'Red';
            if ($r < 100 && $g > 200 && $b < 100) return 'Green';
            if ($r < 100 && $g < 100 && $b > 200) return 'Blue';
            if ($r > 200 && $g > 200 && $b < 100) return 'Yellow';
            if ($r > 200 && $g < 100 && $b > 200) return 'Pink';
            if ($r < 100 && $g > 200 && $b > 200) return 'Cyan';
            if ($r > 200 && $g > 100 && $b < 150) return 'Orange';
            if ($r > 150 && $g < 100 && $b < 100) return 'Dark Red';
            if ($r < 100 && $g > 150 && $b < 100) return 'Dark Green';
            if ($r < 100 && $g < 100 && $b > 150) return 'Dark Blue';
            if ($r > 200 && $g > 200 && $b > 200) return 'White';
            if ($r < 50 && $g < 50 && $b < 50) return 'Black';
            if ($r > 100 && $g > 100 && $b > 100 && $r < 200 && $g < 200 && $b < 200) return 'Gray';
        }
        
        return $hex;
    }
}