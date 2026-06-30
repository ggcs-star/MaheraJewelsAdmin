<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySetting extends Model
{
    protected $fillable = [
        'delivery_fee',
        'platform_fee',
        'tax_percent',
        'free_delivery_above',
        'auto_confirm_enabled',
'auto_confirm_minutes'
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'free_delivery_above' => 'decimal:2',
    ];

    public static function getSettings()
    {
        return self::first();
    }
}