<?php

declare(strict_types=1);

namespace App\Services\Amazon;

use App\Models\AmazonOrder;
use App\Models\AmazonSyncLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SellingPartnerApi\SellingPartnerApi;
use SellingPartnerApi\Seller\OrdersV0\Requests\GetOrders;
use SellingPartnerApi\Seller\OrdersV0\Requests\GetOrder;
use App\Models\AmazonOrderItem;
use SellingPartnerApi\Seller\OrdersV0\Requests\GetOrderItems;
use Throwable;
use App\Models\ProductVariant;

class AmazonOrderService
{
    /**
     * Sync interval in minutes.
     */
    private const SYNC_INTERVAL = 5;

    /**
     * Chunk size for bulk upserts.
     */
    private const CHUNK_SIZE = 200;

    /**
     * Amazon Marketplace ID.
     */
    private string $marketplaceId;

    /**
     * Seller ID.
     */
    private string $sellerId;

    /**
     * SP-API Client instance.
     *
     * @var mixed
     */
    protected $client;

    /**
     * Current Sync Log.
     */
    protected ?AmazonSyncLog $syncLog = null;

    /**
     * Total Orders Processed.
     */
    protected int $ordersProcessed = 0;

    /**
     * Total Items Processed.
     */
    protected int $itemsProcessed = 0;

    /**
     * Total Orders Created.
     */
    protected int $ordersCreated = 0;

    /**
     * Total Orders Updated.
     */
    protected int $ordersUpdated = 0;

    /**
     * Total Failed Orders.
     */
    protected int $failedOrders = 0;

    /**
     * Constructor.
     */
public function __construct()
{
    $this->marketplaceId = config('services.amazon.marketplace_id');
    $this->sellerId = config('services.amazon.seller_id');

    $api = new SellingPartnerApi(
        clientId: config('services.amazon.client_id'),
        clientSecret: config('services.amazon.client_secret'),
        refreshToken: config('services.amazon.refresh_token'),
        endpoint: \SellingPartnerApi\Enums\Endpoint::EU,
    );

    $this->client = $api->seller();
}

    /**
     * Run a full sync cycle: fetch orders, fetch items, persist, log.
     *
     * @return AmazonSyncLog
     */
    public function syncOrders(): AmazonSyncLog
    {
        $startTime = microtime(true);
        $this->resetCounters();
$this->syncLog = AmazonSyncLog::create([
    'module'      => 'orders',
    'api_name'    => 'getOrders',
    'http_method' => 'GET',
    'status'      => 'success',
    'synced_at'   => now(),
]);

        Log::channel('amazon')->info('Amazon sync started', [
            'sync_log_id' => $this->syncLog->id,
        ]);

        try {
            $orders = $this->fetchOrders();

            if ($orders->isEmpty()) {
                $this->logSync('success', null, $startTime);

                Log::channel('amazon')->info('Amazon sync finished — no new orders', [
                    'sync_log_id' => $this->syncLog->id,
                ]);

                return $this->syncLog;
            }

            foreach ($orders->chunk(self::CHUNK_SIZE) as $orderChunk) {
                foreach ($orderChunk as $order) {
                    $this->syncSingleOrder($order);
                }
            }

            $status = $this->failedOrders > 0 ? 'partial' : 'success';
            $this->logSync($status, null, $startTime);

            Log::channel('amazon')->info('Amazon sync completed', [
                'sync_log_id'      => $this->syncLog->id,
                'orders_processed' => $this->ordersProcessed,
                'items_processed'  => $this->itemsProcessed,
                'orders_created'   => $this->ordersCreated,
                'orders_updated'   => $this->ordersUpdated,
                'failed_orders'    => $this->failedOrders,
                'execution_time'   => round(microtime(true) - $startTime, 2) . 's',
            ]);

            return $this->syncLog;
        } catch (Throwable $e) {

    Log::channel('amazon')->error('Amazon sync failed', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);

    $this->logSync('failed', $e->getMessage(), $startTime);

    throw $e;
}
    }

    /**
     * Fetch all orders updated since the last successful sync.
     *
     * @return Collection<int, object>
     */
    public function fetchOrders(): Collection
    {
        $createdAfter = Carbon::now()
    ->subMonths(2)
    ->utc();

        $allOrders = collect();
        $nextToken = null;

        try {
            do {
$response = $this->client->orders()->getOrders(
    marketplaceIds: [$this->marketplaceId],
    createdAfter: $createdAfter->format('Y-m-d\TH:i:s\Z'),
    nextToken: $nextToken,
);
 $data = $response->json();

$orders = collect($data['payload']['Orders'] ?? []);


            foreach ($orders as $order) {
    $allOrders->push(json_decode(json_encode($order)));
}

                $nextToken = $data['payload']['NextToken'] ?? null;
            } while ($nextToken !== null);
        } catch (Throwable $e) {
            $this->handleApiException($e, 'fetchOrders');
            throw $e;
        }

        return $allOrders;
    }

    /**
     * Fetch a single order by Amazon Order ID.
     *
     * @param string $amazonOrderId
     * @return object|null
     */
    public function fetchOrder(string $amazonOrderId): ?object
    {
        try {
            $response = $this->client->orders()->getOrder(
                new GetOrder(orderId: $amazonOrderId)
            );

            $data = $response->json();

return (object)($data['payload'] ?? []);
        } catch (Throwable $e) {
            $this->handleApiException($e, 'fetchOrder', ['amazon_order_id' => $amazonOrderId]);
            throw $e;
        }
    }

    /**
     * Fetch order items for a given Amazon Order ID.
     *
     * @param string $amazonOrderId
     * @return Collection<int, object>
     */
    public function fetchOrderItems(string $amazonOrderId): Collection
    {
        $items     = collect();
        $nextToken = null;

        try {
            do {
             $response = $this->client->orders()->getOrderItems(
    orderId: $amazonOrderId,
    nextToken: $nextToken,
);

$data = $response->json();

$orderItems = $data['payload']['OrderItems'] ?? [];

foreach ($orderItems as $item) {
    $items->push(json_decode(json_encode($item)));
}

$nextToken = $data['payload']['NextToken'] ?? null;
            } while ($nextToken !== null);
        } catch (Throwable $e) {
            $this->handleApiException($e, 'fetchOrderItems', ['amazon_order_id' => $amazonOrderId]);
            throw $e;
        }

        return $items;
    }

    /**
     * Sync a single order: fetch its items and persist both order + items.
     *
     * @param object $order Order DTO from getOrders()
     * @return void
     */
    public function syncSingleOrder(object $order): void
    {
$amazonOrderId = $order->AmazonOrderId ?? null;
        if ($amazonOrderId === null) {
            $this->failedOrders++;

            Log::channel('amazon')->warning('Skipped order with missing amazonOrderId', [
                'order' => $order,
            ]);

            return;
        }

        try {
            $items = $this->fetchOrderItems($amazonOrderId);
            $address = null;

            try {
                $addressResponse = $this->client->orders()->getOrderAddress($amazonOrderId);

            

               
            } catch (\Throwable $e) {
                Log::channel('amazon')->warning('Unable to fetch order address', [
                    'amazon_order_id' => $amazonOrderId,
                    'message' => $e->getMessage(),
                ]);
            }

            if ($items->isEmpty()) {
                Log::channel('amazon')->warning('Order has no items', [
                    'amazon_order_id' => $amazonOrderId,
                ]);
                return;
            }

            DB::transaction(function () use ($order, $items, $amazonOrderId) {
                foreach ($items as $item) {
                    $wasRecentlyCreated = $this->saveOrder($order, $item);

                    if ($wasRecentlyCreated) {
                        $this->ordersCreated++;
                    } else {
                        $this->ordersUpdated++;
                    }

                    $this->itemsProcessed++;
                }
            });

            $this->ordersProcessed++;
        } catch (Throwable $e) {
            $this->failedOrders++;

            Log::channel('amazon')->error('Failed to sync single order', [
                'amazon_order_id' => $amazonOrderId,
                'message'         => $e->getMessage(),
            ]);
        }
    }

    /**
     * Upsert a single order item row.
     * Returns true if a new row was created, false if updated.
     *
     * @param object $order Order-level DTO
     * @param object $item Item-level DTO
     * @return bool
     */
 public function saveOrder(object $order, object $item): bool
{
    
    // Save Order
    $amazonOrder = AmazonOrder::updateOrCreate(
        [
            'amazon_order_id' => $order->AmazonOrderId,
        ],
       [
    'marketplace_id'                     => $order->MarketplaceId ?? null,
    'sales_channel'                      => $order->SalesChannel ?? 'Amazon',
    'order_status'                       => $order->OrderStatus ?? null,
    'order_type'                         => $order->OrderType ?? null,

    'purchase_date'                      => $order->PurchaseDate ?? null,
    'last_update_date'                   => $order->LastUpdateDate ?? null,

    'earliest_ship_date'                 => $order->EarliestShipDate ?? null,
    'latest_ship_date'                   => $order->LatestShipDate ?? null,

    'earliest_delivery_date'             => $order->EarliestDeliveryDate ?? null,
    'latest_delivery_date'               => $order->LatestDeliveryDate ?? null,

    'fulfillment_channel'                => $order->FulfillmentChannel ?? null,

    'shipment_service_level_category'    => $order->ShipmentServiceLevelCategory ?? null,
    'ship_service_level'                 => $order->ShipServiceLevel ?? null,
    'easy_ship_shipment_status'          => $order->EasyShipShipmentStatus ?? null,

    'payment_method'                     => $order->PaymentMethod ?? null,

    'order_total'                        => isset($order->OrderTotal)
        ? (float)$order->OrderTotal->Amount
        : 0,

    'currency'                           => isset($order->OrderTotal)
        ? $order->OrderTotal->CurrencyCode
        : (isset($item->ItemPrice)
            ? $item->ItemPrice->CurrencyCode
            : 'INR'),

    'number_of_items_shipped'            => $order->NumberOfItemsShipped ?? 0,
    'number_of_items_unshipped'          => $order->NumberOfItemsUnshipped ?? 0,

    'is_prime'                           => $order->IsPrime ?? false,
    'is_premium_order'                   => $order->IsPremiumOrder ?? false,
    'is_business_order'                  => $order->IsBusinessOrder ?? false,
    'customer_name'                      => $order->ShippingAddress->Name ?? null,
    'shipping_city'                      => $order->ShippingAddress->City ?? null,
    'shipping_state'                     => $order->ShippingAddress->StateOrRegion ?? null,
    'shipping_postal_code'               => $order->ShippingAddress->PostalCode ?? null,
    'shipping_country'                   => $order->ShippingAddress->CountryCode ?? null,

    'synced_at'                          => now(),

    'raw_response'                       => json_decode(json_encode($order), true),
]
    );

    // Check if Item already exists
    $existing = AmazonOrderItem::where([
        'amazon_order_id' => $order->AmazonOrderId,
        'amazon_order_item_id' => $item->OrderItemId,
    ])->first();
    $variant = ProductVariant::where('sku_suffix', $item->SellerSKU ?? '')->first();

    // Save Order Item
    AmazonOrderItem::updateOrCreate(
    [
        'amazon_order_id' => $order->AmazonOrderId,
        'amazon_order_item_id' => $item->OrderItemId,
    ],
    [
        'amazon_order_db_id'   => $amazonOrder->id,

        'seller_sku'           => $item->SellerSKU ?? null,
        'asin'                 => $item->ASIN ?? null,
        'title'                => $item->Title ?? null,

        'quantity_ordered'     => (int)($item->QuantityOrdered ?? 0),
        'quantity_shipped'     => (int)($item->QuantityShipped ?? 0),

        'item_price'           => isset($item->ItemPrice)
            ? (float)$item->ItemPrice->Amount
            : 0,

        'currency'             => isset($item->ItemPrice)
            ? $item->ItemPrice->CurrencyCode
            : 'INR',

        // 🔥 SKU Mapping
        'product_id'           => $variant?->product_id,
        'product_variant_id'   => $variant?->id,
        'sku_matched'          => $variant ? true : false,

        'raw_response'         => json_decode(json_encode($item), true),
    ]
);

    return $existing === null;
}
    public function updateOrder(string $amazonOrderId, string $amazonOrderItemId, array $values): bool
    {
      return (bool) AmazonOrderItem::query()
    ->where('amazon_order_id', $amazonOrderId)
    ->where('amazon_order_item_id', $amazonOrderItemId)
    ->update($values);
    }

    /**
     * Total Amazon Sold Quantity (Unshipped + PartiallyShipped + Shipped).
     *
     * @param string|null $sku
     * @return int
     */
    public function calculateAmazonSold(?string $sku = null): int
    {
        return AmazonOrder::soldQuantity($sku);
    }

    /**
     * Total Pending Quantity.
     *
     * @param string|null $sku
     * @return int
     */
    public function calculatePending(?string $sku = null): int
    {
        return AmazonOrder::pendingQuantity($sku);
    }

    /**
     * Total Cancelled Quantity.
     *
     * @param string|null $sku
     * @return int
     */
    public function calculateCancelled(?string $sku = null): int
    {
        return AmazonOrder::cancelledQuantity($sku);
    }

    /**
     * Total Shipped Quantity.
     *
     * @param string|null $sku
     * @return int
     */
    public function calculateShipped(?string $sku = null): int
    {
        return AmazonOrder::shippedQuantity($sku);
    }

    /**
     * Timestamp of the last successful sync (used as the createdAfter
     * cursor for the next fetchOrders() call).
     *
     * @return Carbon|null
     */
 public function getLastSync(): ?Carbon
{
    $lastLog = AmazonSyncLog::query()
        ->whereIn('status', ['success', 'warning'])
        ->orderByDesc('synced_at')
        ->first();

    return $lastLog?->synced_at;
}

    /**
     * Persist the final state of the current sync log.
     *
     * @param string $status
     * @param string|null $errorMessage
     * @param float $startTime
     * @return void
     */
    protected function logSync(string $status, ?string $errorMessage, float $startTime): void
    {
        if ($this->syncLog === null) {
            return;
        }

  $this->syncLog->update([
    'status' => $status === 'partial'
        ? 'warning'
        : ($status === 'failed' ? 'failed' : 'success'),

    'message'        => $errorMessage,
    'execution_time' => round(microtime(true) - $startTime, 2),
    'synced_at'      => now(),
]);
    }

    /**
     * Centralized API exception handling: logs and re-throws so callers
     * can decide sync-level status (success/partial/failed).
     *
     * @param Throwable $e
     * @param string $context
     * @param array<string, mixed> $extra
     * @return void
     */
    protected function handleApiException(Throwable $e, string $context, array $extra = []): void
    {
        $isRateLimit = str_contains(strtolower($e->getMessage()), 'quota')
            || str_contains(strtolower($e->getMessage()), 'throttl')
            || str_contains(strtolower($e->getMessage()), '429');

        Log::channel('amazon')->error('Amazon API exception', array_merge([
            'context'      => $context,
            'message'      => $e->getMessage(),
            'is_rate_limit'=> $isRateLimit,
        ], $extra));
    }

    /**
     * Reset per-run counters before starting a new sync.
     *
     * @return void
     */
    protected function resetCounters(): void
    {
        $this->ordersProcessed = 0;
        $this->itemsProcessed  = 0;
        $this->ordersCreated   = 0;
        $this->ordersUpdated   = 0;
        $this->failedOrders    = 0;
    }
}