<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformPricing extends Model
{
    protected $table = 'platform_pricing'; 
 protected $fillable = [
    'platform_product_id',
    'product_variant_id',
    'price',
    'discount_type',
    'discount_value',
    'final_price',
    'quantity',      
    'currency',
    'status',
];


public function platformProduct()
{
    return $this->belongsTo(PlatformProduct::class, 'platform_product_id');
}


    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
