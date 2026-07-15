<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'name',
        'description',
        'code',
        'coupon_type',
        'discount_type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',

        'bank_id',
        'card_type',

        // Coupon Scope
        'category_id',
        'subcategory_id',
        'product_id',
        'one_time_per_user',
    ];

    protected $casts = [
        'starts_at'          => 'datetime',
        'expires_at'         => 'datetime',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
        'is_active'          => 'boolean',
        'one_time_per_user'  => 'boolean',
    ];

    /**
     * Bank Relation
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Platforms Relation
     */
    public function platforms()
    {
        return $this->belongsToMany(
            Platform::class,
            'coupon_platform'
        )->withTimestamps();
    }

    /**
     * Category Relation
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
public function subCategory()
{
    return $this->belongsTo(Category::class, 'subcategory_id');
}
    /**
     * Product Relation
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}