<?php

namespace App\Transformers;

use App\Models\Product;
use App\Services\ProductPricingService;
use Illuminate\Support\Facades\Storage;
use App\Helpers\S3Helper;

class ProductListTransformer
{
    public static function transform(Product $product): array
    {
        $bestPricing = ProductPricingService::getBestPricing($product);
        $imageUrl = null;

        if (
            is_array($product->gallery_images) &&
            count($product->gallery_images) > 0
        ) {
            $imageUrl = S3Helper::url(
                str_replace('\\', '/', $product->gallery_images[0])
            );
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'brand' => $product->brand,
            'product_price' => $product->product_price,
            'click_count' => $product->click_count ?? 0,

            'image_url' => $imageUrl,
            'gallery_images' => $product->getGalleryImagesPublicAttribute(),
            'price' => $bestPricing?->price,
            'final_price' => $bestPricing?->final_price,

            'discount' => $bestPricing ? [
                'type' => $bestPricing->discount_type,
                'value' => $bestPricing->discount_value,
            ] : null,

            // ✅ PO Summary Data (First variant with PO)
            'po_summary' => self::getPOSummary($product),

            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }

    /**
     * ✅ Get PO summary from variants
     */
    private static function getPOSummary($product): ?array
    {
        foreach ($product->variants as $variant) {
            if (isset($variant->has_po) && $variant->has_po) {
                return [
                    'quantity' => $variant->po_quantity ?? 0,
                    'purchase_price' => $variant->po_purchase_price ?? 0,
                    'po_number' => $variant->po_number ?? null,
                ];
            }
        }
        return null;
    }
}