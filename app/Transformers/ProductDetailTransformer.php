<?php

namespace App\Transformers;

use App\Models\Product;
use App\Services\ProductPricingService;

class ProductDetailTransformer
{
    public static function transform(Product $product): array
    {
        $listing = $product->platformListings->first();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'short_description' => $product->short_description,
            'image_url' => $product->image_url,
            'gallery_images' => $product->gallery_images ?? [],

            'category' => [
                'id' => $product->category?->id,
                'name' => $product->category?->name,
            ],

            'stock' => $listing?->platform_stock ?? 0,
            'in_stock' => ($listing?->platform_stock ?? 0) > 0,

            'variants' => $product->variants->map(function ($variant) use ($listing) {

                $pricing = ProductPricingService::getVariantPricing(
                    $variant,
                    $listing
                );

                return [
                    'id' => $variant->id,
                    'variant_type' => $variant->variant_type,
                    'variant_value' => $variant->variant_value,
                    'sku' => $variant->sku_suffix,
                    'price' => $pricing?->price,
                    'final_price' => $pricing?->final_price,
                    'currency' => $pricing?->currency ?? 'INR',
                    'quantity' => $pricing?->quantity ?? 0,
                    'image_url' => $variant->image_url,
                    'in_stock' => ($pricing?->quantity ?? 0) > 0,
                    'discount' => $pricing ? [
                        'type' => $pricing->discount_type,
                        'value' => $pricing->discount_value,
                    ] : null,
                ];
            }),
        ];
    }
}
