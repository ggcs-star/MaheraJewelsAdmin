<?php

namespace App\Transformers;

use App\Models\Product;
use App\Services\ProductPricingService;

class ProductListTransformer
{
    public static function transform(Product $product): array
    {
        $bestPricing = ProductPricingService::getBestPricing($product);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image_url' => $product->image_url,
            'brand' => $product->brand,

            'price' => $bestPricing?->price,
            'final_price' => $bestPricing?->final_price,

            'discount' => $bestPricing ? [
                'type' => $bestPricing->discount_type,
                'value' => $bestPricing->discount_value,
            ] : null,
        ];
    }
}
