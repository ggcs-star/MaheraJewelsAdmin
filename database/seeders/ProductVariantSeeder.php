<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::where('slug', 'womens-premium-cotton-t-shirt')->first();

        if (!$product) return;

        // Size Variants
        $sizes = ['S', 'M', 'L'];

        foreach ($sizes as $index => $size) {
            ProductVariant::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'variant_type' => 'size',
                    'variant_value' => $size,
                ],
                [
                    'sku_suffix' => '-' . $size,
                    'image_url' => "/images/products/tsh-wom-001-size-" . strtolower($size) . ".jpg",
                    'sort_order' => $index + 1,
                    'status' => 'active',
                ]
            );
        }

        // Color Variants
        $colors = ['Red', 'Blue'];

        foreach ($colors as $index => $color) {
            ProductVariant::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'variant_type' => 'color',
                    'variant_value' => $color,
                ],
                [
                    'sku_suffix' => '-' . strtoupper($color),
                    'image_url' => "/images/products/tsh-wom-001-" . strtolower($color) . ".jpg",
                    'sort_order' => $index + 1,
                    'status' => 'active',
                ]
            );
        }
    }
}
