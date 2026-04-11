<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


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
        'product_price',
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
        'product_price' => 'decimal:2',
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

protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
// ✅ USER SIDE ONLY (safe)
public function getImageUrlPublicAttribute()
{
    if (!$this->image_url) return null;

    return Storage::disk('s3')->url(
        str_replace('\\', '/', $this->image_url)
    );
}

public function getGalleryImagesPublicAttribute()
{
    if (!$this->gallery_images) return [];

    $images = is_array($this->gallery_images)
        ? $this->gallery_images
        : json_decode($this->gallery_images, true);

    return collect($images)->map(function ($path) {
        return Storage::disk('s3')->url(
            str_replace('\\', '/', $path)
        );
    })->toArray();
}

}
