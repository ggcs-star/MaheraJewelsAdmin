<?php

namespace App\Services\Amazon;

use App\Models\PlatformPricing;
use App\Models\PlatformProduct;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;

class AmazonProductSyncService
{
    protected AmazonClient $client;

    public function __construct()
    {
        $this->client = new AmazonClient();
    }

    public function sync(): array
    {
        $variants = ProductVariant::with('product')
            ->where('status', 'active')
            ->whereHas('product')
            ->get();

        $success = 0;
        $failed = 0;

        foreach ($variants as $variant) {

            try {

                if ($this->syncProduct($variant)) {
                    $success++;
                } else {
                    $failed++;
                }

            } catch (\Throwable $e) {

                Log::error('Amazon Product Sync Failed', [
                    'variant_id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'sku'        => $variant->sku_suffix,
                    'message'    => $e->getMessage(),
                ]);

                $failed++;
            }
        }

        return [
            'success' => $success,
            'failed'  => $failed,
        ];
    }

    private function syncProduct(ProductVariant $variant): bool
    {
        if (!$variant->product) {
            return false;
        }

        $sku = trim($variant->sku_suffix);

        try {

            $response = $this->client
                ->seller()
                ->listingsItems()
                ->getListingsItem(
                    sellerId: $this->client->sellerId(),
                    sku: $sku,
                    marketplaceIds: [
                        $this->client->marketplaceId()
                    ],
                    issueLocale: null,
                    includedData: [
                        'summaries',
                        'fulfillmentAvailability'
                    ]
                );

        } catch (\Throwable $e) {

            Log::error('Amazon API Exception', [
                'sku'       => $sku,
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return false;
        }

        $data = $response->json();

        Log::info('Amazon Response', [
            'sku'      => $sku,
            'response' => $data,
        ]);

        if (!isset($data['sku'])) {
            return false;
        }

        // Amazon Available Stock
        $amazonStock = (int) data_get(
            $data,
            'fulfillmentAvailability.0.quantity',
            0
        );

        $platformProduct = PlatformProduct::updateOrCreate(
    [
        'platform_id'        => 7,
        'product_variant_id' => $variant->id,
    ],
    [
        'product_id'         => $variant->product_id,
        'product_variant_id' => $variant->id,
        'platform_sku'       => $sku,
        'platform_stock'     => $amazonStock,
        'platform_price'     => $variant->selling_price,
        'status'             => 'active',
        'sync_status'        => 'synced',
        'last_synced_at'     => now(),
        'is_enabled'         => true,
        'error_message'      => null,
    ]
);
        PlatformPricing::updateOrCreate(
            [
                'platform_product_id' => $platformProduct->id,
                'product_variant_id'  => $variant->id,
            ],
            [
                'price'       => $variant->selling_price,
                'final_price' => $variant->selling_price,
                'quantity'    => $amazonStock,
                'currency'    => 'INR',
                'status'      => 'active',
            ]
        );

        return true;
    }
}