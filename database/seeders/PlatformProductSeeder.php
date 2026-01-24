<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Platform;
use App\Models\PlatformProduct;

class PlatformProductSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::where('slug', 'womens-premium-cotton-t-shirt')->first();
        $amazon = Platform::where('name', 'amazon')->first();
        $flipkart = Platform::where('name', 'flipkart')->first();
        $website = Platform::where('name', 'own_website')->first();

        if (!$product || !$amazon || !$flipkart || !$website) {
            $this->command->error('Product or Platform missing.');
            return;
        }

        PlatformProduct::updateOrCreate(
            ['product_id' => $product->id, 'platform_id' => $amazon->id],
            [
                'platform_product_id' => 'B08XYZ1234',
                'platform_listing_id' => 'LISTING-123456',
                'platform_url' => 'https://www.amazon.in/dp/B08XYZ1234',
                'platform_price' => 399,
                'platform_stock' => 20,
                'sync_status' => 'synced',
                'last_synced_at' => now(),
                'is_enabled' => true,
            ]
        );

        PlatformProduct::updateOrCreate(
            ['product_id' => $product->id, 'platform_id' => $flipkart->id],
            [
                'platform_product_id' => 'FLIPKART-PROD-123',
                'platform_listing_id' => 'FLIPKART-LISTING-456',
                'platform_url' => 'https://www.flipkart.com/product/FLIPKART-PROD-123',
                'platform_price' => 389,
                'platform_stock' => 15,
                'sync_status' => 'synced',
                'last_synced_at' => now(),
                'is_enabled' => true,
            ]
        );

        PlatformProduct::updateOrCreate(
            ['product_id' => $product->id, 'platform_id' => $website->id],
            [
                'platform_url' => 'https://ourwebsite.com/products/womens-premium-cotton-t-shirt',
                'platform_price' => 350,
                'platform_stock' => 30,
                'sync_status' => 'synced',
                'last_synced_at' => now(),
                'is_enabled' => true,
            ]
        );
    }
}
