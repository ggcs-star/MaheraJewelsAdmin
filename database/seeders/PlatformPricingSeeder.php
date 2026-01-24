<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlatformProduct;
use App\Models\ProductVariant;
use App\Models\PlatformPricing;

class PlatformPricingSeeder extends Seeder
{
    public function run(): void
    {
        $amazonProduct = PlatformProduct::whereHas('platform', fn($q) => $q->where('name','amazon'))->first();
        $flipkartProduct = PlatformProduct::whereHas('platform', fn($q) => $q->where('name','flipkart'))->first();

        if (!$amazonProduct || !$flipkartProduct) {
            $this->command->error('Platform products missing.');
            return;
        }

        $variants = ProductVariant::where('product_id', $amazonProduct->product_id)->get();

        // Amazon base price
        PlatformPricing::updateOrCreate(
            ['platform_product_id' => $amazonProduct->id, 'product_variant_id' => null],
            [
                'price' => 400,
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'final_price' => 360,
                'currency' => 'INR',
                'status' => 'active',
            ]
        );

        // Amazon variant pricing
        foreach ($variants as $variant) {
            $discount = match($variant->variant_value) {
                'S' => 10,
                'M' => 15,
                'L' => 20,
                default => 0
            };

            $final = 400 - (400 * $discount / 100);

            PlatformPricing::updateOrCreate(
                ['platform_product_id' => $amazonProduct->id, 'product_variant_id' => $variant->id],
                [
                    'price' => 400,
                    'discount_type' => 'percentage',
                    'discount_value' => $discount,
                    'final_price' => $final,
                    'currency' => 'INR',
                    'status' => 'active',
                ]
            );
        }

        // Flipkart pricing
        foreach ($variants as $variant) {
            $discount = match($variant->variant_value) {
                'S' => 30,
                'M' => 40,
                'L' => 50,
                default => 0
            };

            $final = 380 - $discount;

            PlatformPricing::updateOrCreate(
                ['platform_product_id' => $flipkartProduct->id, 'product_variant_id' => $variant->id],
                [
                    'price' => 380,
                    'discount_type' => 'fixed',
                    'discount_value' => $discount,
                    'final_price' => $final,
                    'currency' => 'INR',
                    'status' => 'active',
                ]
            );
        }
    }
}
