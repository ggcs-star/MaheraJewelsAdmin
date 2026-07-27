<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmazonOrder extends Model
{
    use HasFactory;
    public const PLATFORM = 'amazon';

    protected $table = 'amazon_orders';

    protected $fillable = [
        'amazon_order_id',
        'marketplace_id',
        'sales_channel',
        'order_status',
        'order_type',
        'purchase_date',
        'last_update_date',
        'earliest_ship_date',
        'latest_ship_date',
        'earliest_delivery_date',
        'latest_delivery_date',
        'fulfillment_channel',
        'shipment_service_level_category',
        'ship_service_level',
        'easy_ship_shipment_status',
        'payment_method',
        'order_total',
        'currency',
        'number_of_items_shipped',
        'number_of_items_unshipped',
        'is_prime',
        'is_premium_order',
        'is_business_order',
        'is_replacement_order',
        'is_access_point_order',
        'is_global_express_enabled',
        'is_ispu',
        'has_regulated_items',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'ship_from_city',
        'ship_from_state',
        'ship_from_postal_code',
        'ship_from_country',
        'synced_at',
        'raw_response'
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'last_update_date' => 'datetime',
        'earliest_ship_date' => 'datetime',
        'latest_ship_date' => 'datetime',
        'earliest_delivery_date' => 'datetime',
        'latest_delivery_date' => 'datetime',
        'synced_at' => 'datetime',

        'raw_response' => 'array',

        'is_prime' => 'boolean',
        'is_premium_order' => 'boolean',
        'is_business_order' => 'boolean',
        'is_replacement_order' => 'boolean',
        'is_access_point_order' => 'boolean',
        'is_global_express_enabled' => 'boolean',
        'is_ispu' => 'boolean',
        'has_regulated_items' => 'boolean',

        'order_total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(
            AmazonOrderItem::class,
            'amazon_order_db_id'
        );
    }
}