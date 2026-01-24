<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformPricing extends Model
{
    protected $table = 'platform_pricing'; // 🔥 IMPORTANT FIX

    protected $fillable = [
        'platform_product_id',
        'product_variant_id',
        'price',
        'discount_type',
        'discount_value',
        'final_price',
        'currency',
        'status',
    ];

    public function platformProduct()
    {
        return $this->belongsTo(PlatformProduct::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
