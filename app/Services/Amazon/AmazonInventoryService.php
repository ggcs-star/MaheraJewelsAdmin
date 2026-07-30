<?php

namespace App\Services\Amazon;

use App\Models\AmazonInventorySync;
use App\Models\PlatformPricing;
use App\Models\PlatformProduct;

class AmazonInventoryService
{
    protected AmazonClient $client;

    public function __construct()
    {
        $this->client = new AmazonClient();
    }

    /**
     * Test inventory by SKU
     */
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
                'fulfillmentAvailability',
            ]
        );

        dd($response->json());
    }

    /**
     * Sync all Amazon inventory
     */
    public function syncInventory(): bool
    {
        $platformProducts = PlatformProduct::with([
            'product',
            'pricing.variant.product',
        ])
            ->where('platform_id', 7)
            ->get();

        foreach ($platformProducts as $platformProduct) {

            foreach ($platformProduct->pricing as $pricing) {

                if (!$pricing->variant || !$pricing->variant->product) {
                    continue;
                }

                $this->syncSingleInventory(
                    $platformProduct,
                    $pricing,
                    $platformProduct->platform_sku
                );
            }
        }

        return true;
    }

    /**
     * Sync single SKU inventory
     */
    private function syncSingleInventory(
        PlatformProduct $platformProduct,
        PlatformPricing $pricing,
        string $sku
    ): void {

        $oldQty = $pricing->quantity;

        try {

            $response = $this->client->seller()->listingsItems()->getListingsItem(
                sellerId: config('services.amazon.seller_id'),
                sku: $sku,
                marketplaceIds: [config('services.amazon.marketplace_id')],
                issueLocale: null,
                includedData: [
                    'summaries',
                    'fulfillmentAvailability',
                ]
            );

            $data = $response->json();

            $quantity = data_get(
                $data,
                'fulfillmentAvailability.0.quantity',
                0
            );

            $pricing->update([
                'quantity' => $quantity,
            ]);

            $platformProduct->update([
                'platform_stock' => $quantity,
                'sync_status' => 'synced',
                'last_synced_at' => now(),
                'error_message' => null,
            ]);

            AmazonInventorySync::create([
                'product_id' => $platformProduct->product_id,
                'product_variant_id' => $platformProduct->product_variant_id,
                'seller_sku' => $sku,
                'asin' => data_get($data, 'summaries.0.asin'),
                'amazon_quantity' => $quantity,
                'erp_quantity_before' => $oldQty,
                'erp_quantity_after' => $quantity,
                'quantity_difference' => $quantity - $oldQty,
                'sync_type' => 'inventory_pull',
                'sync_status' => 'success',
                'message' => 'Inventory synced successfully',
                'synced_at' => now(),
                'raw_response' => $data,
            ]);

        } catch (\Throwable $e) {

            $platformProduct->update([
                'sync_status' => 'failed',
                'last_synced_at' => now(),
                'error_message' => $e->getMessage(),
            ]);

            AmazonInventorySync::create([
                'product_id' => $platformProduct->product_id,
                'product_variant_id' => $platformProduct->product_variant_id,
                'seller_sku' => $sku,
                'sync_type' => 'inventory_pull',
                'sync_status' => 'failed',
                'message' => $e->getMessage(),
                'synced_at'=> now(),
            ]);
        }
    }

    /**
     * Get inventory by SKU
     */
    public function getInventoryBySku($sku)
    {
        return $this->client->seller()->listingsItems()->getListingsItem(
            sellerId: config('services.amazon.seller_id'),
            sku: $sku,
            marketplaceIds: [config('services.amazon.marketplace_id')],
            issueLocale: null,
            includedData: [
                'summaries',
                'fulfillmentAvailability'
            ]
        );
    }
}