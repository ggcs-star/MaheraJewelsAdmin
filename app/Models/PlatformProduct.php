<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformProduct extends Model
{
    protected $fillable = [
        'platform_id',
        'product_id',
        'platform_sku',
        'platform_price',
        'platform_stock',
        'platform_product_id',
        'platform_listing_id',
        'platform_url',
        'status',
        'sync_status',
        'last_synced_at',
        'error_message',
        'is_enabled',
    ];

    protected $casts = [
        'platform_price' => 'decimal:2',
        'platform_stock' => 'integer',
        'last_synced_at' => 'datetime',
        'is_enabled' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
    public function pricing()
{
    return $this->hasMany(PlatformPricing::class);
}

}
