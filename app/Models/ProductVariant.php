<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'variant_id',
        'variant_value_id',
        'height',
        'width',
        'color',
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

    public function platformPricings()
    {
        return $this->hasMany(\App\Models\PlatformPricing::class, 'product_variant_id');
    }
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function value()
    {
        return $this->belongsTo(VariantValue::class, 'variant_value_id');
    }
     public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'product_variant_id');
    }

    // ✅ YEH RELATIONSHIP ADD KARO
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    // ✅ YEH RELATIONSHIP ADD KARO (Optional - Invoice Items ke liye)
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'product_variant_id');
    }
    public function platformProducts()
    {
        return $this->hasMany(PlatformProduct::class,'product_variant_id');
    }

}