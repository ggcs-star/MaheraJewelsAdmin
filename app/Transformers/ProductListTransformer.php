<?php

namespace App\Transformers;

use App\Models\Product;
use App\Services\ProductPricingService;
use Illuminate\Support\Facades\Storage;

class ProductListTransformer
{
    public static function transform(Product $product): array
    {
        $bestPricing = ProductPricingService::getBestPricing($product);

        // ✅ PRODUCT IMAGE = gallery_images ka first image
        $imageUrl = null;

        if (
            is_array($product->gallery_images) &&
            count($product->gallery_images) > 0
        ) {
            $imageUrl = Storage::disk('s3')->url(
                str_replace('\\', '/', $product->gallery_images[0])
            );
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'brand' => $product->brand,
            'product_price' => $product->product_price,

            // ⭐ YAHI MAIN CHEEZ HAI
            'image_url' => $imageUrl,

            'price' => $bestPricing?->price,
            'final_price' => $bestPricing?->final_price,

            'discount' => $bestPricing ? [
                'type' => $bestPricing->discount_type,
                'value' => $bestPricing->discount_value,
            ] : null,
        ];
    }
}
