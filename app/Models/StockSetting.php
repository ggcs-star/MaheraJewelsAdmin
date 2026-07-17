<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockSetting extends Model
{
    protected $fillable = [
        'threshold',        // Website threshold
        'offline_threshold', // Offline threshold (new)
        'admin_email',
        'last_checked_at',
    ];
}