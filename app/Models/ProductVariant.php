<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'variant_type',
        'variant_value',
        'quantity',
        'purchase_price',
        'selling_price',
        'total_price',   
        'sku_suffix',
        'image_url',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'quantity'        => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'total_price'    => 'decimal:2',
        'sort_order'     => 'integer',
    ];
}


