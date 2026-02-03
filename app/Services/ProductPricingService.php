<?php

namespace App\Services;

use App\Models\Product;

class ProductPricingService
{
    public static function getBestPricing(Product $product)
    {
        $listing = $product->platformListings->first();

        if (!$listing) {
            return null;
        }

        return $product->variants
            ->flatMap(fn ($variant) =>
                $variant->platformPricings
                    ->where('platform_product_id', $listing->id)
            )
            ->sortBy(fn ($p) => $p->final_price ?? $p->price)
            ->first();
    }

    public static function getVariantPricing($variant, $listing)
    {
        return $variant->platformPricings
            ->where('platform_product_id', $listing?->id)
            ->first();
    }
}
