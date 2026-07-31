<?php

namespace App\Services\Amazon;

use App\Models\PlatformProduct;
use App\Models\PlatformPricing;
use App\Models\ProductVariant;
use App\Models\Product;
class AmazonInventoryService
{
    protected AmazonClient $client;

    public function __construct()
    {
        $this->client = new AmazonClient();
    }

    public function testInventory(string $sku)
{
   $response = $this->client->seller()->listingsItems()->getListingsItem(
    sellerId: config('services.amazon.seller_id'),
    sku: $sku,
    marketplaceIds: [config('services.amazon.marketplace_id')],
    issueLocale: null,
    includedData: [
        'summaries',
        'attributes',
        'fulfillmentAvailability'
    ]
);


}
    public function getInventory()
    {
        $seller = $this->client->seller()->seller();

        /*
         *
         * Amazon Inventory API call yaha hoga
         *
         */

        return $seller;
    }
public function syncInventory()
{
    $platformProducts = PlatformProduct::with([
        'product',
        'pricing.variant.product'
    ])
    ->where('platform_id', 7) // Amazon Platform ID
    ->get();



    foreach ($platformProducts as $platformProduct) {

        foreach ($platformProduct->pricing as $pricing) {

            if (!$pricing->variant || !$pricing->variant->product) {
                continue;
            }

            $sku = $pricing->variant->product->sku . $pricing->variant->sku_suffix;

            try {

                $response = $this->client->seller()->listingsItems()->getListingsItem(
                    sellerId: config('services.amazon.seller_id'),
                    sku: $sku,
                    marketplaceIds: [config('services.amazon.marketplace_id')],
                    issueLocale: null,
                    includedData: ['fulfillmentAvailability']
                );

                $data = $response->json();

                $quantity = data_get($data, 'fulfillmentAvailability.0.quantity', 0);

                $pricing->update([
                    'quantity' => $quantity,
                ]);

                $platformProduct->update([
                    'platform_stock'  => $quantity,
                    'sync_status'     => 'success',
                    'last_synced_at'  => now(),
                    'error_message'   => null,
                ]);

            } catch (\Throwable $e) {

                $platformProduct->update([
                    'sync_status'     => 'failed',
                    'last_synced_at'  => now(),
                    'error_message'   => $e->getMessage(),
                ]);
            }
        }
    }

    return true;
}

private function syncSingleInventory(
    PlatformProduct $platformProduct,
    PlatformPricing $pricing,
    string $sku
) {
    try {

        $response = $this->client->seller()->listingsItems()->getListingsItem(
            sellerId: config('services.amazon.seller_id'),
            sku: $sku,
            marketplaceIds: [config('services.amazon.marketplace_id')],
            issueLocale: null,
            includedData: ['fulfillmentAvailability']
        );

        $data = $response->json();

        $quantity = data_get($data, 'fulfillmentAvailability.0.quantity', 0);

        $pricing->update([
            'quantity' => $quantity,
        ]);

        $platformProduct->update([
            'platform_stock' => $quantity,
'sync_status' => 'synced',
            'last_synced_at' => now(),
            'error_message' => null,
        ]);

    } catch (\Throwable $e) {

        $platformProduct->update([
            'sync_status' => 'failed',
            'last_synced_at' => now(),
            'error_message' => $e->getMessage(),
        ]);
    }
}
    public function getInventoryBySku($sku)
    {
        $inventory = $this->getInventory();

        /*
         * SKU Filter
         */

        return $inventory;
    }
}