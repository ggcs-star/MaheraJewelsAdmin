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
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_top_selling' => 'boolean',
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
    return $this->hasMany(ProductVariant::class);
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


}
