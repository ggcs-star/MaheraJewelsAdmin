<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'shipping_address_id',
        'billing_address_id',
        'subtotal',
        'tax',
        'shipping',
        'discount',
        'total',
        'status',
        'payment_status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number =
                'ORD-' . now()->format('Ymd') . '-' . rand(100, 999);
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

