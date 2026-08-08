<?php

namespace App\Services\Amazon;

use App\Models\PlatformPricing;
use App\Models\PlatformProduct;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use Saloon\Exceptions\Request\Statuses\TooManyRequestsException;

class AmazonProductSyncService
{
    protected AmazonClient $client;

    public function __construct()
    {
        $this->client = new AmazonClient();
    }

   public function sync(): array
{
    $success = 0;
    $failed = 0;

    ProductVariant::with([
            'product',
            'platformProducts' => function ($query) {
                $query->where('platform_id', 7);
            }
        ])
        ->where('status', 'active')
        ->whereHas('product')
        ->where(function ($query) {

            // New products (never synced)
            $query->whereDoesntHave('platformProducts', function ($q) {
                $q->where('platform_id', 7);
            });

            // Updated after last sync
            $query->orWhereHas('platformProducts', function ($q) {
                $q->where('platform_id', 7)
                    ->whereColumn(
                        'product_variants.updated_at',
                        '>',
                        'platform_products.last_synced_at'
                    );
            });

            // Failed products
            $query->orWhereHas('platformProducts', function ($q) {
                $q->where('platform_id', 7)
                    ->where('sync_status', 'failed');
            });
        })
        ->chunkById(20, function ($variants) use (&$success, &$failed) {

            foreach ($variants as $variant) {

                try {

                    if ($this->syncProduct($variant)) {

                        $success++;

                    } else {

                        PlatformProduct::updateOrCreate(
                            [
                                'platform_id' => 7,
                                'product_variant_id' => $variant->id,
                            ],
                            [
                                'product_id' => $variant->product_id,
                                'platform_sku' => $variant->sku_suffix,
                                'sync_status' => 'failed',
                                'error_message' => 'Sync failed',
                            ]
                        );

                        $failed++;
                    }

                } catch (\Throwable $e) {

                    Log::error('Amazon Product Sync Failed', [
                        'variant_id' => $variant->id,
                        'product_id' => $variant->product_id,
                        'sku' => $variant->sku_suffix,
                        'message' => $e->getMessage(),
                    ]);

                    PlatformProduct::updateOrCreate(
                        [
                            'platform_id' => 7,
                            'product_variant_id' => $variant->id,
                        ],
                        [
                            'product_id' => $variant->product_id,
                            'platform_sku' => $variant->sku_suffix,
                            'sync_status' => 'failed',
                            'error_message' => $e->getMessage(),
                        ]
                    );

                    $failed++;
                }

                usleep(1200000); // 1.2 sec
            }

            sleep(5);
        });

    return [
        'success' => $success,
        'failed' => $failed,
    ];
}

    private function syncProduct(ProductVariant $variant): bool
    {
        if (!$variant->product) {
            return false;
        }

        $sku = trim($variant->sku_suffix);

        $response = null;

        $maxAttempts = 4;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {

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

                // Success
                break;

            } catch (TooManyRequestsException $e) {

                Log::warning('Amazon Rate Limit Hit', [
                    'sku'     => $sku,
                    'attempt' => $attempt,
                ]);

                if ($attempt == $maxAttempts) {

                    Log::error('Amazon Rate Limit Failed', [
                        'sku' => $sku,
                    ]);

                    return false;
                }

                sleep(pow(2, $attempt)); // 2,4,8 seconds

            }catch (\Throwable $e) {

    Log::error('Amazon API Exception', [
        'sku'       => $sku,
        'exception' => get_class($e),
        'message'   => $e->getMessage(),
    ]);

    PlatformProduct::updateOrCreate(
        [
            'platform_id' => 7,
            'product_variant_id' => $variant->id,
        ],
        [
            'product_id' => $variant->product_id,
            'platform_sku' => $sku,
            'sync_status' => 'failed',
            'error_message' => $e->getMessage(),
        ]
    );

    return false;
}
        }

        if (!$response) {
            return false;
        }

        $data = $response->json();

        Log::info('Amazon Response', [
            'sku'      => $sku,
            'response' => $data,
        ]);

    if (!isset($data['sku'])) {

    PlatformProduct::updateOrCreate(
        [
            'platform_id' => 7,
            'product_variant_id' => $variant->id,
        ],
        [
            'product_id' => $variant->product_id,
            'platform_sku' => $sku,
            'sync_status' => 'failed',
            'error_message' => 'SKU not found on Amazon',
        ]
    );

    return false;
}

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