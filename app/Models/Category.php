<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\S3Helper;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'is_featured',
        'visibility',
        'status',
    ];

    /**
     * Parent category relationship.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Child categories relationship.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Products belonging to this category.
     * Supports multiple categories per product.
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'category_product',
            'category_id',
            'product_id'
        )->withTimestamps();
    }

    /**
     * Convert category image paths to public S3 URLs.
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (
            str_starts_with($value, 'http://') ||
            str_starts_with($value, 'https://')
        ) {
            return $value;
        }

        return S3Helper::url($value);
    }
}
