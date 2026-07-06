<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductClick extends Model
{
   protected $fillable = [
    'product_id',
    'click_count',
    'user_id',
    'ip_address',
    'device_id',
    'session_id',
];
}