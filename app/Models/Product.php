<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'short_description',
        'category_id',
        'supplier_id',
        'brand',
        'cost_price',
        'base_selling_price',
        'image_url',
        'gallery_images',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'is_featured',
        'is_top_selling',
        'visibility',
        'status',
        'warehouse_id',
        'expected_delivery_date',
        'payment_terms',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_top_selling' => 'boolean',
        'expected_delivery_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
 public function variants()
{
    return $this->hasMany(
        \App\Models\ProductVariant::class,
        'product_id', // FK in product_variants table
        'id'          // PK in products table
    );
}

public function platforms()
{
    return $this->belongsToMany(Platform::class, 'platform_products')
        ->withPivot([
            'platform_sku',
            'platform_price',
            'platform_stock',
            'status'
        ])
        ->withTimestamps();
}
public function platformListings()
{
    return $this->hasMany(PlatformProduct::class);
}

public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}

}
