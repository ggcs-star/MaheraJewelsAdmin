<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [

        'purchase_order_id',

        'product_id',

        'product_variant_id',

        'purchase_price',

        'online_price',

        'offline_price',

        'quantity',

        'total',

    ];

    protected $casts = [

        'purchase_price' => 'decimal:2',

        'online_price' => 'decimal:2',

        'offline_price' => 'decimal:2',

        'quantity' => 'integer',

        'total' => 'decimal:2',

    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_id');
    }
}