<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AmazonInventorySync extends Model
{
    use HasFactory;

    protected $table = 'amazon_inventory_syncs';

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'seller_sku',
        'asin',
        'amazon_quantity',
        'erp_quantity_before',
        'erp_quantity_after',
        'quantity_difference',
        'sync_type',
        'sync_status',
        'message',
        'synced_at',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'synced_at' => 'datetime',
    ];
}