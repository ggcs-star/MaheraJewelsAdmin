<?php

namespace App\Transformers;

use App\Models\Product;
use App\Services\ProductPricingService;
use Illuminate\Support\Facades\Storage;

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

            // ✅ MAIN IMAGE (FULL URL)
            'image_url' => $product->image_url
                ? Storage::disk('s3')->url($product->image_url)
                : null,

            'gallery_images' => is_array($product->gallery_images)
    ? collect($product->gallery_images)
        ->map(fn ($img) =>
            Storage::disk('s3')->url(
                str_replace('\\', '/', $img)
            )
        )
        ->values()
    : [],

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
                    'variant_type' => optional($variant->variant)->name,
                    'variant_value' => optional($variant->value)->value,
                    'sku' => $variant->sku_suffix,

                    'price' => $pricing?->price,
                    'final_price' => $pricing?->final_price,
                    'currency' => $pricing?->currency ?? 'INR',
                    'quantity' => $pricing?->quantity ?? 0,

                    // ✅ VARIANT IMAGE (FULL URL)
                    'image_url' => $variant->image_url
                        ? Storage::disk('s3')->url($variant->image_url)
                        : null,

                    'in_stock' => ($pricing?->quantity ?? 0) > 0,

                    'discount' => $pricing ? [
                        'type' => $pricing->discount_type,
                        'value' => $pricing->discount_value,
                    ] : null,
                ];
            })->values(),
        ];
    }
}
