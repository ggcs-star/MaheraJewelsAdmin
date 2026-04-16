<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';


    protected $attributes = [
        'gateway' => 'razorpay'
    ];

    protected $fillable = [

        'user_id',
        'order_id',

        'amount',
        'currency',

        'payment_method',
        'payment_status',
        'gateway',

        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',

        'client_ip',

        'coupon_code',

        'payment_meta',
        'razorpay_payload',

        'paid_at'

    ];

    protected $casts = [

        'paid_at' => 'datetime',

        'payment_meta' => 'array',

        'razorpay_payload' => 'array',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isPending(): bool
    {
        return $this->payment_status === self::STATUS_PENDING;
    }


    public function isPaid(): bool
    {
        return $this->payment_status === self::STATUS_PAID;
    }


    public function isFailed(): bool
    {
        return $this->payment_status === self::STATUS_FAILED;
    }


    public function isRefunded(): bool
    {
        return $this->payment_status === self::STATUS_REFUNDED;
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::STATUS_PAID);
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', self::STATUS_PENDING);
    }

}