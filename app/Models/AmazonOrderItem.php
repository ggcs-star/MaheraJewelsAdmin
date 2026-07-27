<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmazonOrderItem extends Model
{
    use HasFactory;

    protected $table = 'amazon_order_items';

    protected $fillable = [
        'amazon_order_db_id',
        'amazon_order_id',
        'amazon_order_item_id',
        'seller_sku',
        'asin',
        'title',
        'quantity_ordered',
        'quantity_shipped',
        'item_price',
        'currency',
        'product_id',
        'product_variant_id',
        'sku_matched',
        'inventory_updated',
        'inventory_updated_at',
        'raw_response',
    ];

    protected $casts = [
        'item_price' => 'decimal:2',
        'raw_response' => 'array',
        'inventory_updated_at' => 'datetime',
        'sku_matched' => 'boolean',
        'inventory_updated' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            AmazonOrder::class,
            'amazon_order_db_id'
        );
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(
            ProductVariant::class,
            'product_variant_id'
        );
    }
}