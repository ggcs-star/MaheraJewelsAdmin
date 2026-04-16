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
];

    protected $casts = [
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function platforms()
    {
        return $this->belongsToMany(
            Platform::class,
            'coupon_platform'
        )->withTimestamps();
    }
}

